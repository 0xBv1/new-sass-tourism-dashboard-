<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class BlogCategoryController extends Controller
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
        $queryBuilder = new QueryBuilder(new BlogCategory, $request);
        $blogCategories = $queryBuilder->build()->paginate();
        $collection = BlogCategoryResource::collection($blogCategories->getCollection());
        $blogCategories->setCollection(collect($collection));
        return $this->send($blogCategories);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        return $this->send(new BlogCategoryResource($blogCategory));
    }
}
