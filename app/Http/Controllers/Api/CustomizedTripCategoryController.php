<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\CustomizedTripCategoryResource;
use App\Models\CustomizedTripCategory;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class CustomizedTripCategoryController extends Controller
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
        $queryBuilder = new QueryBuilder(new CustomizedTripCategory, $request);
        $customizedTripCategories = $queryBuilder->build()->get();
        return $this->send(CustomizedTripCategoryResource::collection($customizedTripCategories));
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $customizedTripCategory = CustomizedTripCategory::findOrFail($id);
        return $this->send(new CustomizedTripCategoryResource($customizedTripCategory));
    }
}
