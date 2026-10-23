<?php

namespace Documentation;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Durations",
 *     description="Duration management endpoints"
 * )
 */
class DurationController extends Controller
{
    /**
     * Search durations or list durations
     * @OA\Get(
     *     path="/api/durations",
     *     operationId="getDurations",
     *     tags={"Durations"},
     *     summary="Search by duration or list durations",
     *     description="When 'search' is provided (duration id or title/description), returns all available tours in that duration sorted ascending. Otherwise returns all enabled durations sorted ascending.",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term: duration id or title/description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function indexDoc() {}

    /**
     * Get duration by slug
     * @OA\Get(
     *     path="/api/durations/{slug}",
     *     operationId="getDurationBySlug",
     *     tags={"Durations"},
     *     summary="Get duration by slug",
     *     description="Returns a duration by its slug",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Duration slug",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful operation", @OA\JsonContent(type="object")),
     *     @OA\Response(response=404, description="Duration not found")
     * )
     */
    public function showDoc() {}
}

/**
 * @OA\Schema(
 *     schema="Duration",
 *     type="object",
 *     title="Duration",
 *     description="Duration model",
 *     required={"id", "title"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="3 Days 2 Nights"),
 *     @OA\Property(property="description", type="string", example="Perfect for a short getaway"),
 *     @OA\Property(property="slug", type="string", example="3-days-2-nights"),
 *     @OA\Property(property="enabled", type="boolean", example=true),
 *     @OA\Property(property="featured", type="boolean", example=false),
 *     @OA\Property(property="display_order", type="integer", example=1),
 *     @OA\Property(property="days", type="integer", example=3),
 *     @OA\Property(property="nights", type="integer", example=2),
 *     @OA\Property(property="duration_type", type="string", enum={"days", "hours", "weeks", "months"}, example="days"),
 *     @OA\Property(property="tours_count", type="integer", example=15),
 *     @OA\Property(property="formatted_duration", type="string", example="3 Days 2 Nights"),
 *     @OA\Property(property="banner", type="string", nullable=true, example="banners/duration-banner.jpg"),
 *     @OA\Property(property="featured_image", type="string", nullable=true, example="images/duration-featured.jpg"),
 *     @OA\Property(property="gallery", type="array", @OA\Items(type="string"), example={"images/gallery1.jpg", "images/gallery2.jpg"}),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(
 *         property="seo",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="meta_title", type="string", example="3 Days 2 Nights Tours"),
 *         @OA\Property(property="meta_description", type="string", example="Discover amazing 3 days 2 nights tour packages"),
 *         @OA\Property(property="meta_keywords", type="string", example="3 days, 2 nights, tours, packages")
 *     ),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Duration")
 *     ),
 *     @OA\Property(
 *         property="parent",
 *         ref="#/components/schemas/Duration",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="tours",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Tour")
 *     )
 * )
 */ 