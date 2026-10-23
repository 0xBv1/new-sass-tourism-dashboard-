<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqCategoryResource;
use App\Models\FaqCategory;

class FaqCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/faq-categories",
     *     tags={"FAQ Categories"},
     *     summary="Get all FAQ categories with their FAQs",
     *     description="Returns a list of all active FAQ categories with their associated FAQs, ordered by display order",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="General Questions"),
     *                 @OA\Property(property="slug", type="string", example="general-questions"),
     *                 @OA\Property(property="description", type="string", example="Common questions about our services"),
     *                 @OA\Property(property="faqs", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="question", type="string"),
     *                     @OA\Property(property="answer", type="string")
     *                 ))
     *             ))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = FaqCategory::active()
            ->ordered()
            ->with(['activeFaqs', 'translations'])
            ->get();

        return FaqCategoryResource::collection($categories);
    }
}

