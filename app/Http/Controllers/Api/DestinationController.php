<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Cache\AppCache;
use Illuminate\Http\Request;
use App\Http\Resources\Api\DestinationResource;
use App\Models\Destination;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class DestinationController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/destinations",
     *     tags={"Destinations"},
     *     summary="Get all destinations",
     *     description="Returns a paginated list of tour destinations",
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $key = 'destinations_'. $request->getQueryString();
//        if (AppCache::has($key)) {
//            $destinations = AppCache::get($key, ['data' => []]);
//            $destinations['data'] = DestinationResource::collection(collect($destinations['data'])->map(fn($d) => new Destination($d)));
//        } else {
            $queryBuilder = new QueryBuilder(new Destination, $request);
            $destinations = $queryBuilder->build()->paginate();
//            AppCache::put($key, $destinations->toArray());
            $collection = DestinationResource::collection($destinations->getCollection());
            $destinations->setCollection(collect($collection));
//        }
        return $this->send($destinations);
    }

    /**
     * @OA\Get(
     *     path="/api/destinations/{slug}",
     *     tags={"Destinations"},
     *     summary="Get destination by slug",
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Destination not found")
     * )
     */
    public function show(Request $request, string $slug)
    {
        $request->merge([
            'slug' => $slug
        ]);
        $queryBuilder = new QueryBuilder(new Destination, $request);
        $destination = $queryBuilder->build()->firstOrFail();
        return $this->send(new DestinationResource($destination));
    }
}
