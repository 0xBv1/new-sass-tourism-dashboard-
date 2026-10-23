<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Http\Resources\Api\TourResource;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Models\Destination;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

/**
 * @OA\Tag(
 *     name="Tours",
 *     description="Tour management endpoints"
 * )
 */
class TourController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/tours",
     *     summary="Get all available tours",
     *     tags={"Tours"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         description="Filter by featured tours only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="destination_id",
     *         in="query",
     *         description="Filter by destination ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="destination_title",
     *         in="query",
     *         description="Filter by destination title (case-insensitive search)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="destination_slug",
     *         in="query",
     *         description="Filter by destination slug (exact match)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="category_title",
     *         in="query",
     *         description="Filter by category title (case-insensitive search)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_slug",
     *         in="query",
     *         description="Filter by category slug (exact match)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="price_min",
     *         in="query",
     *         description="Minimum price filter",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="price_max",
     *         in="query",
     *         description="Maximum price filter",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="duration_min",
     *         in="query",
     *         description="Minimum duration in days",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="duration_max",
     *         in="query",
     *         description="Maximum duration in days",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tours retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Start with a fresh query to avoid QueryBuilder conflicts with destination parameters
            $query = Tour::query();

            // Filter by enabled tours only
            $query->where('enabled', true);

            // Filter by featured if requested
            if ($request->has('featured') && $request->boolean('featured')) {
                $query->where('featured', true);
            }

            // Filter by destination
            if ($request->has('destination_id')) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('destination_id', $request->destination_id);
                });
            }

            // Filter by destination title (case-insensitive search)
            if ($request->has('destination_title') && !empty($request->destination_title)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->whereTranslationLike('title', '%' . $request->destination_title . '%');
                });
            }

            // Filter by destination slug (exact match)
            if ($request->has('destination_slug') && !empty($request->destination_slug)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('slug', $request->destination_slug);
                });
            }

            // Handle other QueryBuilder parameters manually
            if ($request->has('title') && !empty($request->title)) {
                $query->whereTranslationLike('title', '%' . $request->title . '%');
            }

            if ($request->has('slug') && !empty($request->slug)) {
                $query->where('slug', 'like', '%' . $request->slug . '%');
            }

            if ($request->has('duration_in_days') && !empty($request->duration_in_days)) {
                $query->where('duration_in_days', $request->duration_in_days);
            }

            // Filter by category
            if ($request->has('category_id')) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            // Filter by category title (case-insensitive search)
            if ($request->has('category_title') && !empty($request->category_title)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->whereTranslationLike('title', '%' . $request->category_title . '%');
                });
            }

            // Filter by category slug (exact match)
            if ($request->has('category_slug') && !empty($request->category_slug)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('slug', $request->category_slug);
                });
            }

            // Filter by price range
            if ($request->has('price_min')) {
                $query->where('adult_price', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('adult_price', '<=', $request->price_max);
            }

            // Filter by duration
            if ($request->has('duration_min')) {
                $query->where('duration_in_days', '>=', $request->duration_min);
            }

            if ($request->has('duration_max')) {
                $query->where('duration_in_days', '<=', $request->duration_max);
            }

            // Load relationships
            $query->with(['destinations', 'categories', 'reviews', 'days']);

            // Order by display order and then by created date
            $query->orderBy('display_order', 'asc')
                  ->orderBy('created_at', 'desc');

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $tours = $query->paginate($perPage);

            // Transform to resource collection
            $collection = TourResource::collection($tours->getCollection());
            $tours->setCollection(collect($collection));

            // Build base response payload
            $response = $tours->toArray();

            // If searching by destination_title, include related categories
            if ($request->has('destination_title') && !empty($request->destination_title)) {
                $destinationIds = Destination::query()
                    ->whereTranslationLike('title', '%' . $request->destination_title . '%')
                    ->pluck('id');

                $categories = collect();

                if ($destinationIds->isNotEmpty()) {
                    $categories = Category::query()
                        ->where('enabled', true)
                        ->whereHas('tours', function ($q) use ($destinationIds) {
                            $q->whereHas('destinations', function ($qd) use ($destinationIds) {
                                $qd->whereIn('destinations.id', $destinationIds);
                            });
                        })
                        ->orderBy('display_order')
                        ->get();
                }

                $response['categories'] = CategoryResource::collection($categories);
            }

            return $this->send($response, 'Tours retrieved successfully');

        } catch (Exception $e) {
            \Log::error('Tour API Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->send(null, 'Error retrieving tours: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tours/stats",
     *     summary="Get tour statistics",
     *     tags={"Tours"},
     *     @OA\Response(
     *         response=200,
     *         description="Tour statistics retrieved successfully"
     *     )
     * )
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $stats = [
                'total_tours' => Tour::where('enabled', true)->count(),
                'featured_tours' => Tour::where('enabled', true)->where('featured', true)->count(),
                'destinations_count' => Tour::where('enabled', true)
                    ->join('tour_destinations', 'tours.id', '=', 'tour_destinations.tour_id')
                    ->distinct('tour_destinations.destination_id')
                    ->count('tour_destinations.destination_id'),
                'categories_count' => Tour::where('enabled', true)
                    ->join('tour_categories', 'tours.id', '=', 'tour_categories.tour_id')
                    ->distinct('tour_categories.category_id')
                    ->count('tour_categories.category_id'),
                'price_range' => [
                    'min' => Tour::where('enabled', true)->min('adult_price') ?? 0,
                    'max' => Tour::where('enabled', true)->max('adult_price') ?? 0,
                    'average' => round(Tour::where('enabled', true)->avg('adult_price') ?? 0, 2),
                ],
                'duration_range' => [
                    'min_days' => Tour::where('enabled', true)->min('duration_in_days') ?? 0,
                    'max_days' => Tour::where('enabled', true)->max('duration_in_days') ?? 0,
                    'average_days' => round(Tour::where('enabled', true)->avg('duration_in_days') ?? 0, 1),
                ],
                'reviews_stats' => [
                    'total_reviews' => Tour::where('enabled', true)->sum('reviews_number') ?? 0,
                    'average_rating' => round(Tour::where('enabled', true)
                        ->where('reviews_number', '>', 0)
                        ->selectRaw('AVG(rates / reviews_number) as avg_rating')
                        ->value('avg_rating') ?? 0, 1),
                ],
            ];

            return $this->send($stats, 'Tour statistics retrieved successfully');

        } catch (Exception $e) {
            return $this->send(null, 'Error retrieving tour statistics: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tours/search/destination",
     *     summary="Search tours by destination",
     *     tags={"Tours"},
     *     @OA\Parameter(
     *         name="destination_id",
     *         in="query",
     *         description="Destination ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="destination_title",
     *         in="query",
     *         description="Destination title (case-insensitive search)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="destination_slug",
     *         in="query",
     *         description="Destination slug (exact match)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="featured",
     *         in="query",
     *         description="Filter by featured tours only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="price_min",
     *         in="query",
     *         description="Minimum price filter",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="price_max",
     *         in="query",
     *         description="Maximum price filter",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tours found successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No destination parameter provided"
     *     )
     * )
     */
    public function searchByDestination(Request $request): JsonResponse
    {
        try {
            // Validate that at least one destination parameter is provided
            if (!$request->has('destination_id') && 
                !$request->has('destination_title') && 
                !$request->has('destination_slug')) {
                return $this->send(null, 'Please provide destination_id, destination_title, or destination_slug', 400);
            }

            // Start with a fresh query to avoid QueryBuilder conflicts with destination parameters
            $query = Tour::query();

            // Filter by enabled tours only
            $query->where('enabled', true);

            // Filter by featured if requested
            if ($request->has('featured') && $request->boolean('featured')) {
                $query->where('featured', true);
            }

            // Filter by destination ID
            if ($request->has('destination_id')) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('destination_id', $request->destination_id);
                });
            }

            // Filter by destination title (case-insensitive search)
            if ($request->has('destination_title') && !empty($request->destination_title)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->whereTranslationLike('title', '%' . $request->destination_title . '%');
                });
            }

            // Filter by destination slug (exact match)
            if ($request->has('destination_slug') && !empty($request->destination_slug)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('slug', $request->destination_slug);
                });
            }

            // Handle other QueryBuilder parameters manually
            if ($request->has('title') && !empty($request->title)) {
                $query->whereTranslationLike('title', '%' . $request->title . '%');
            }

            if ($request->has('slug') && !empty($request->slug)) {
                $query->where('slug', 'like', '%' . $request->slug . '%');
            }

            if ($request->has('duration_in_days') && !empty($request->duration_in_days)) {
                $query->where('duration_in_days', $request->duration_in_days);
            }

            // Filter by price range
            if ($request->has('price_min')) {
                $query->where('adult_price', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('adult_price', '<=', $request->price_max);
            }

            // Load relationships
            $query->with(['destinations', 'categories', 'reviews']);

            // Order by display order and then by created date
            $query->orderBy('display_order', 'asc')
                  ->orderBy('created_at', 'desc');

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $tours = $query->paginate($perPage);

            // Transform to resource collection
            $collection = TourResource::collection($tours->getCollection());
            $tours->setCollection(collect($collection));

            return $this->send($tours, 'Tours found successfully');

        } catch (Exception $e) {
            \Log::error('Tour Search API Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->send(null, 'Error searching tours: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tours/{slug}",
     *     summary="Get tour details by slug",
     *     tags={"Tours"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Tour slug",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="includes",
     *         in="query",
     *         description="Comma-separated list of relationships to include",
     *         required=false,
     *         @OA\Schema(type="string", example="destinations,categories,reviews,days")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tour details retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tour not found"
     *     )
     * )
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        try {
            // Get includes from request
            $includes = $request->get('includes', '');
            $includes = explode(',', $includes);
            $includes = array_filter($includes); // Remove empty values
            
            // Default includes
            $defaultIncludes = ['destinations', 'categories', 'seo', 'days'];
            $includes = array_unique(array_merge($defaultIncludes, $includes));

            // Available relationships
            $availableIncludes = [
                'destinations', 'categories', 'options', 'days', 'seasons', 
                'reviews', 'seo', 'suppliers'
            ];
            
            // Filter to only allowed includes
            $includes = array_intersect($includes, $availableIncludes);

            $tour = Tour::where('slug', $slug)
                ->where('enabled', true)
                ->with($includes)
                ->first();

            if (!$tour) {
                return $this->send(null, 'Tour not found', 404);
            }

            return $this->send(new TourResource($tour), 'Tour details retrieved successfully');

        } catch (Exception $e) {
            return $this->send(null, 'Error retrieving tour details: ' . $e->getMessage(), 500);
        }
    }
}
