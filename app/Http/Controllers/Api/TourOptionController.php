<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\TourOptionResource;
use App\Models\TourOption;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class TourOptionController extends Controller
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
        $queryBuilder = new QueryBuilder(new TourOption, $request);
        $tourOptions = $queryBuilder->build()->paginate();
        $collection = TourOptionResource::collection($tourOptions->getCollection());
        $tourOptions->setCollection(collect($collection));
        return $this->send($tourOptions);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $tourOption = TourOption::findOrFail($id);
        return $this->send(new TourOptionResource($tourOption));
    }

    /**
     * Get tour options by tour ID
     *
     * @param  int  $tourId
     * @return JsonResponse
     */
    public function getByTour(int $tourId)
    {
        $tourOptions = TourOption::whereHas('tours', function ($query) use ($tourId) {
            $query->where('tours.id', $tourId);
        })->get();
        
        return $this->send(TourOptionResource::collection($tourOptions));
    }
}
