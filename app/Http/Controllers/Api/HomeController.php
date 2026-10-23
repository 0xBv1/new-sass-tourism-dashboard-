<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use App\Models\Slider;
use App\Models\Employee;
use App\Models\Tour;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Destination;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Home",
 *     description="Home page data endpoints"
 * )
 */
class HomeController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/home",
     *     summary="Get all home page components",
     *     tags={"Home"},
     *     description="Returns all components needed for the home page including sliders, team members, featured tours, testimonials, categories, and destinations",
     *     @OA\Parameter(
     *         name="featured_tours_limit",
     *         in="query",
     *         description="Number of featured tours to return (default: 6)",
     *         required=false,
     *         @OA\Schema(type="integer", default=6)
     *     ),
     *     @OA\Parameter(
     *         name="testimonials_limit",
     *         in="query",
     *         description="Number of featured testimonials to return (default: 6)",
     *         required=false,
     *         @OA\Schema(type="integer", default=6)
     *     ),
     *     @OA\Parameter(
     *         name="categories_limit",
     *         in="query",
     *         description="Number of featured categories to return (default: 8)",
     *         required=false,
     *         @OA\Schema(type="integer", default=8)
     *     ),
     *     @OA\Parameter(
     *         name="destinations_limit",
     *         in="query",
     *         description="Number of featured destinations to return (default: 6)",
     *         required=false,
     *         @OA\Schema(type="integer", default=6)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="slider", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="key", type="string", example="homepage"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="slides", type="array", @OA\Items(type="object"))
     *                 ),
     *                 @OA\Property(property="team", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="position", type="string"),
     *                     @OA\Property(property="image", type="string"),
     *                     @OA\Property(property="social_links", type="object")
     *                 )),
     *                 @OA\Property(property="featured_tours", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="testimonials", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="featured_categories", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="featured_destinations", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $featuredToursLimit = request('featured_tours_limit', 6);
        $testimonialsLimit = request('testimonials_limit', 6);
        $categoriesLimit = request('categories_limit', 8);
        $destinationsLimit = request('destinations_limit', 6);

        // Get homepage slider
        $slider = Slider::getActiveByKey(Slider::KEY_HOMEPAGE);

        // Get active team members
        $team = Employee::active()
            ->ordered()
            ->get();

        // Get featured tours
        $featuredTours = Tour::where('featured', true)
            ->where('enabled', true)
            ->with(['categories', 'destinations'])
            ->orderBy('display_order')
            ->orderBy('id', 'desc')
            ->limit($featuredToursLimit)
            ->get();

        // Get featured testimonials/comments
        $testimonials = Comment::where('is_featured', true)
            ->where('is_approved', true)
            ->with(['tour:id,title,slug'])
            ->orderBy('created_at', 'desc')
            ->limit($testimonialsLimit)
            ->get();

        // Get featured categories
        $featuredCategories = Category::where('featured', true)
            ->where('enabled', true)
            ->orderBy('display_order')
            ->orderBy('id', 'desc')
            ->limit($categoriesLimit)
            ->get();

        // Get featured destinations
        $featuredDestinations = Destination::where('featured', true)
            ->where('enabled', true)
            ->orderBy('display_order')
            ->orderBy('id', 'desc')
            ->limit($destinationsLimit)
            ->get();

        $data = [
            'slider' => $slider,
            'team' => $team,
            'featured_tours' => $featuredTours,
            'testimonials' => $testimonials,
            'featured_categories' => $featuredCategories,
            'featured_destinations' => $featuredDestinations,
        ];

        return $this->send(new HomeResource($data));
    }
}
