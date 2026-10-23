<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\BlogResource;
use App\Models\Blog;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class BlogController extends Controller
{
    use HasApiResponse;

    /**
     * @OA\Get(
     *     path="/api/blogs",
     *     tags={"Blog"},
     *     summary="Get all blog posts",
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $queryBuilder = new QueryBuilder(new Blog, $request);
        $blogs = $queryBuilder->build()->paginate();
        $collection = BlogResource::collection($blogs->getCollection());
        $blogs->setCollection(collect($collection));
        return $this->send($blogs);
    }

    /**
     * @OA\Get(
     *     path="/api/blogs/{slug}",
     *     tags={"Blog"},
     *     summary="Get blog post by slug",
     *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Blog not found")
     * )
     */
    public function show(Request $request, string $slug)
    {
        $request->merge([
            'slug' => $slug
        ]);
        $queryBuilder = new QueryBuilder(new Blog, $request);
        $category = $queryBuilder->build()->firstOrFail();
        return $this->send(new BlogResource($category));
    }
}
