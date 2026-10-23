<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\AmenityResource;
use App\Models\Amenity;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class AmenityController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/amenities",
     *     tags={"Amenities"},
     *     summary="Get all amenities",
     *     description="Returns a list of all hotel/room amenities",
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Amenity, $request);
        $amenities = $queryBuilder->build()->paginate();
        $collection = AmenityResource::collection($amenities->getCollection());
        $amenities->setCollection(collect($collection));
        return $this->send($amenities);
    }

    /**
     * @OA\Get(
     *     path="/api/amenities/{id}",
     *     tags={"Amenities"},
     *     summary="Get amenity by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Amenity not found")
     * )
     */
    public function show(mixed $id)
    {
        $amenity = Amenity::findOrFail($id);
        return $this->send(new AmenityResource($amenity));
    }
}
