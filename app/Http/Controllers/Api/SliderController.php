<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;

class SliderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sliders",
     *     tags={"Sliders"},
     *     summary="Get all active sliders",
     *     description="Returns a list of all active sliders with their slides",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="home_slider"),
     *                 @OA\Property(property="name", type="string", example="Home Page Slider"),
     *                 @OA\Property(property="slides", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="subtitle", type="string"),
     *                     @OA\Property(property="image", type="string"),
     *                     @OA\Property(property="button_text", type="string"),
     *                     @OA\Property(property="button_link", type="string")
     *                 ))
     *             ))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $sliders = Slider::active()
            ->with(['activeSlides'])
            ->get();

        return SliderResource::collection($sliders);
    }

    /**
     * @OA\Get(
     *     path="/api/sliders/{key}",
     *     tags={"Sliders"},
     *     summary="Get slider by key",
     *     description="Returns a specific slider by its unique key",
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         required=true,
     *         description="Slider key (e.g., home_slider, about_banner)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="key", type="string", example="home_slider"),
     *                 @OA\Property(property="name", type="string", example="Home Page Slider"),
     *                 @OA\Property(property="slides", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Slider not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Slider not found.")
     *         )
     *     )
     * )
     */
    public function show(string $key)
    {
        $slider = Slider::getActiveByKey($key);

        if (!$slider) {
            return response()->json([
                'success' => false,
                'message' => 'Slider not found.',
            ], 404);
        }

        return new SliderResource($slider);
    }
}

