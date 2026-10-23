<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Cache\AppCache;
use Illuminate\Http\Request;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\TourResource;
use App\Models\Category;
use App\Models\Tour;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     description="Returns a paginated list of tour categories",
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $key = 'categories_'. $request->getQueryString();
//        if (AppCache::has($key)) {
//            $categories = AppCache::get($key, ['data' => []]);
//            $categories['data'] =  CategoryResource::collection(collect($categories['data'])->map(fn($c) => new Category($c)));
//        } else {
            $queryBuilder = new QueryBuilder(new Category, $request);
            $categories = $queryBuilder->build()->paginate();
//            AppCache::put($key, $categories->toArray());
            $collection = CategoryResource::collection($categories->getCollection());
            $categories->setCollection(collect($collection));
//        }
        return $this->send($categories);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{slug}",
     *     tags={"Categories"},
     *     summary="Get category by slug",
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="include_tours", in="query", description="Include category tours", @OA\Schema(type="boolean")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(Request $request, string $slug)
    {
         $request->merge([
            'slug' => $slug
        ]);
        $queryBuilder = new QueryBuilder(new Category, $request);
        $category = $queryBuilder->build()->firstOrFail();
        
        // If tours are requested, load them with all details
        if ($request->has('include_tours') && $request->boolean('include_tours')) {
            $category->load(['tours' => function($query) {
                $query->where('enabled', true)
                      ->with(['destinations', 'categories', 'reviews', 'days', 'seasons', 'options'])
                      ->orderBy('display_order', 'asc')
                      ->orderBy('created_at', 'desc');
            }]);
        }
        
        return $this->send(new CategoryResource($category));
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{slug}/tours",
     *     tags={"Categories"},
     *     summary="Get all tours for a category",
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="price_min", in="query", @OA\Schema(type="number")),
     *     @OA\Parameter(name="price_max", in="query", @OA\Schema(type="number")),
     *     @OA\Parameter(name="destination_slug", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function tours(Request $request, string $slug)
    {
        try {
            // Find the category by slug
            $category = Category::where('slug', $slug)
                ->where('enabled', true)
                ->firstOrFail();

            // Build tours query
            $query = $category->tours()
                ->where('enabled', true)
                ->with(['destinations', 'categories', 'reviews', 'days', 'seasons', 'options']);

            // Apply filters
            if ($request->has('featured') && $request->boolean('featured')) {
                $query->where('featured', true);
            }

            if ($request->has('price_min')) {
                $query->where('adult_price', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('adult_price', '<=', $request->price_max);
            }

            if ($request->has('duration_min')) {
                $query->where('duration_in_days', '>=', $request->duration_min);
            }

            if ($request->has('duration_max')) {
                $query->where('duration_in_days', '<=', $request->duration_max);
            }

            if ($request->has('destination_id')) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('destination_id', $request->destination_id);
                });
            }

            if ($request->has('destination_title') && !empty($request->destination_title)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->whereTranslationLike('title', '%' . $request->destination_title . '%');
                });
            }

            if ($request->has('destination_slug') && !empty($request->destination_slug)) {
                $query->whereHas('destinations', function ($q) use ($request) {
                    $q->where('slug', $request->destination_slug);
                });
            }

            // Order by display order and then by created date
            $query->orderBy('display_order', 'asc')
                  ->orderBy('created_at', 'desc');

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $tours = $query->paginate($perPage);

            // Transform to resource collection
            $collection = TourResource::collection($tours->items());
            
            // Build response with category info
            $response = [
                'data' => $collection,
                'current_page' => $tours->currentPage(),
                'last_page' => $tours->lastPage(),
                'per_page' => $tours->perPage(),
                'total' => $tours->total(),
                'from' => $tours->firstItem(),
                'to' => $tours->lastItem(),
                'category' => new CategoryResource($category)
            ];

            return $this->send($response, 'Tours for category retrieved successfully');

        } catch (Exception $e) {
            Log::error('Category Tours API Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'category_slug' => $slug,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->send(null, 'Error retrieving tours for category: ' . $e->getMessage(), 500);
        }
    }
}
