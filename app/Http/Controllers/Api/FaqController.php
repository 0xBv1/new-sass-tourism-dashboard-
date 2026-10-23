<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Cache\AppCache;
use Illuminate\Http\Request;
use App\Http\Resources\Api\FaqResource;
use App\Models\Faq;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\Query\QueryBuilder;
use Exception;

class FaqController extends Controller
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
        $queryBuilder = new QueryBuilder(new Faq, $request);
        $faqs = $queryBuilder->build()->paginate()->toArray();
        return $this->send($faqs);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return JsonResponse
     */
    public function show(mixed $id)
    {
        $faq = Faq::findOrFail($id);
        return $this->send(new FaqResource($faq));
    }
}
