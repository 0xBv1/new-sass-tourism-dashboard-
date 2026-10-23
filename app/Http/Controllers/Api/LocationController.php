<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\LocationResource;
use App\Models\Location;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class LocationController extends Controller
{
    use HasApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Location, $request);
        $locations = $queryBuilder->build()->paginate();
        $collection = LocationResource::collection($locations->getCollection());
        $locations->setCollection(collect($collection));
        return $this->send($locations);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $location = Location::findOrFail($id);
        return $this->send(new LocationResource($location));
    }
}
