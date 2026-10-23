<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\HotelResource;
use App\Models\Hotel;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Get all hotels",
     *     description="Returns a paginated list of all hotels with filtering support",
     *     @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", description="Items per page", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="city_id", in="query", description="Filter by city", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="stars", in="query", description="Filter by star rating", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object", @OA\Property(property="data", type="array", @OA\Items(type="object")))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Hotel, $request);
        $hotels = $queryBuilder->build()->paginate();
        $collection = HotelResource::collection($hotels->getCollection());
        $hotels->setCollection(collect($collection));
        return $this->send($hotels);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Get hotel by ID or slug",
     *     description="Returns a specific hotel with its rooms and amenities",
     *     @OA\Parameter(name="id", in="path", required=true, description="Hotel ID or slug", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Hotel not found")
     * )
     */
    public function show(mixed $id)
    {
        $hotel = Hotel::where(function ($query) use ($id) {
            $query->where('id', $id)->orWhere('slug', $id);
        })->firstOrFail();
        return $this->send(new HotelResource($hotel));
    }
}
