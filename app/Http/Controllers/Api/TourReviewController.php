<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TourReviewRequest;
use App\Http\Resources\Api\TourReviewResource;
use App\Models\TourReview;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TourReviewController extends Controller
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
        $queryBuilder = new QueryBuilder(new TourReview, $request);
        $tourReviews = $queryBuilder->build()->paginate();
        $collection = TourReviewResource::collection($tourReviews->getCollection());
        $tourReviews->setCollection(collect($collection));
        return $this->send($tourReviews);
    }


    public function store(TourReviewRequest $request)
    {
        $review = TourReview::create($request->getSanitized());
        $review->tour->increment('rates', $review->rate);
        $review->tour->increment('reviews_number');
        return $this->send(
            message: __('messages.tour.reviews.added')
        );
    }
}
