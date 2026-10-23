<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TourResource;
use App\Models\Client;
use App\Models\Tour;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class WishlistController extends Controller
{
    use HasApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index()
    {
        $tours = auth()->user()->toursWishlist()->paginate();
        $collection = TourResource::collection($tours->getCollection()->map(function ($tour){
            $tour->setAttribute('wishlisted_exists', true);
            return $tour;
        }));
        $tours->setCollection(collect($collection));
        return $this->send($tours);
    }

    public function toggle(Tour $tour)
    {
        auth()->user()->toursWishlist()->toggle($tour);
        return $this->noContent();
    }
}
