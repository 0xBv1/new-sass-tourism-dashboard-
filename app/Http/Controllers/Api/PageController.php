<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\PageResource;
use App\Models\Page;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class PageController extends Controller
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
        $queryBuilder = new QueryBuilder(new Page, $request);
        $pages = $queryBuilder->build()->paginate();
        $collection = PageResource::collection($pages->getCollection());
        $pages->setCollection(collect($collection));
        return $this->send($pages);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $key
     * @param Request $request
     * @return JsonResponse
     */
    public function show(mixed $key, Request $request)
    {
        $request->merge([
            'key' => $key
        ]);
//        $queryBuilder = new QueryBuilder(new Page, $request);
//        $page = $queryBuilder->build()->firstOrFail();
        $includes = $request->get('includes', []);
        // Handle both string (comma-separated) and array formats
        if (is_string($includes)) {
            $includes = explode(',', $includes);
        }
        // Ensure it's an array
        if (!is_array($includes)) {
            $includes = [];
        }
        $includes[] = 'seo';
        $page = Page::where('key', $key)->with(array_unique($includes))->firstOrFail();
        return $this->send(new PageResource($page));
    }
}
