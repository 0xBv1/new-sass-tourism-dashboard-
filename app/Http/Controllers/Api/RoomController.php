<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\RoomResource;
use App\Models\Room;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class RoomController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Get all rooms",
     *     description="Returns a paginated list of all hotel rooms",
     *     @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", description="Items per page", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="hotel_id", in="query", description="Filter by hotel", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Room, $request);
        $rooms = $queryBuilder->build()->with(['children', 'adults', 'amenities', 'hotel'])->paginate();
        $collection = RoomResource::collection($rooms->getCollection());
        $rooms->setCollection(collect($collection));
        return $this->send($rooms);
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Get room by ID or slug",
     *     description="Returns a specific room with its amenities and pricing",
     *     @OA\Parameter(name="id", in="path", required=true, description="Room ID or slug", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Room not found")
     * )
     */
    public function show(mixed $id)
    {
        $room = Room::with(['children', 'adults', 'amenities', 'hotel'])
            ->where(function ($query) use ($id) {
                $query->where('id', $id)->orWhere('slug', $id);
            })->firstOrFail();
        return $this->send(new RoomResource($room));
    }
}
