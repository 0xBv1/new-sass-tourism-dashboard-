<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourPackage;
use App\Services\TourPricingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Pricing",
 *     description="Tour pricing calculation and package endpoints"
 * )
 */
class TourPricingController extends Controller
{
    protected TourPricingService $pricingService;

    public function __construct(TourPricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * @OA\Post(
     *     path="/api/tours/{tour}/calculate-price",
     *     summary="Calculate tour price with all applicable discounts and rules",
     *     tags={"Pricing"},
     *     @OA\Parameter(
     *         name="tour",
     *         in="path",
     *         required=true,
     *         description="Tour ID or slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"travel_date", "adults"},
     *             @OA\Property(property="travel_date", type="string", format="date", example="2025-02-15"),
     *             @OA\Property(property="adults", type="integer", example=2, minimum=1),
     *             @OA\Property(property="children", type="integer", example=1, minimum=0),
     *             @OA\Property(property="infants", type="integer", example=0, minimum=0),
     *             @OA\Property(property="duration_days", type="integer", example=5),
     *             @OA\Property(property="nationality", type="string", example="US"),
     *             @OA\Property(property="promo_code", type="string", example="SUMMER20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Price calculation breakdown",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="base_adult_price", type="number", example=100.00),
     *                 @OA\Property(property="base_child_price", type="number", example=50.00),
     *                 @OA\Property(property="base_infant_price", type="number", example=0.00),
     *                 @OA\Property(property="adults", type="integer", example=2),
     *                 @OA\Property(property="children", type="integer", example=1),
     *                 @OA\Property(property="infants", type="integer", example=0),
     *                 @OA\Property(property="final_adult_price", type="number", example=85.00),
     *                 @OA\Property(property="final_child_price", type="number", example=42.50),
     *                 @OA\Property(property="final_infant_price", type="number", example=0.00),
     *                 @OA\Property(property="adult_total", type="number", example=170.00),
     *                 @OA\Property(property="child_total", type="number", example=42.50),
     *                 @OA\Property(property="infant_total", type="number", example=0.00),
     *                 @OA\Property(property="subtotal", type="number", example=212.50),
     *                 @OA\Property(property="savings", type="number", example=37.50),
     *                 @OA\Property(property="savings_percentage", type="number", example=15.0),
     *                 @OA\Property(property="total", type="number", example=212.50),
     *                 @OA\Property(property="adjustments", type="array", @OA\Items(
     *                     @OA\Property(property="type", type="string", example="early_bird"),
     *                     @OA\Property(property="label", type="string", example="Book 30+ days early"),
     *                     @OA\Property(property="applied", type="boolean", example=true)
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tour not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function calculatePrice(Request $request, Tour $tour): JsonResponse
    {
        $validated = $request->validate([
            'travel_date' => 'required|date|after:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'nationality' => 'nullable|string|max:5',
            'promo_code' => 'nullable|string|max:50',
        ]);

        $context = [
            'travel_date' => $validated['travel_date'],
            'duration_days' => $validated['duration_days'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'promo_code' => $validated['promo_code'] ?? null,
        ];

        $breakdown = $this->pricingService->calculatePrice(
            $tour,
            Carbon::parse($validated['travel_date']),
            $validated['adults'],
            $validated['children'] ?? 0,
            $validated['infants'] ?? 0,
            $context
        );

        return response()->json([
            'success' => true,
            'data' => $breakdown,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/tours/{tour}/pricing-info",
     *     summary="Get tour pricing information summary",
     *     tags={"Pricing"},
     *     @OA\Parameter(
     *         name="tour",
     *         in="path",
     *         required=true,
     *         description="Tour ID or slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tour pricing information",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="base_prices", type="object",
     *                     @OA\Property(property="adult", type="number", example=100.00),
     *                     @OA\Property(property="child", type="number", example=50.00),
     *                     @OA\Property(property="infant", type="number", example=0.00)
     *                 ),
     *                 @OA\Property(property="has_date_specific", type="boolean", example=true),
     *                 @OA\Property(property="has_tiered_pricing", type="boolean", example=true),
     *                 @OA\Property(property="has_duration_pricing", type="boolean", example=false),
     *                 @OA\Property(property="has_early_bird", type="boolean", example=true),
     *                 @OA\Property(property="has_last_minute", type="boolean", example=false),
     *                 @OA\Property(property="has_dynamic_pricing", type="boolean", example=false),
     *                 @OA\Property(property="has_custom_rules", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tour not found")
     * )
     */
    public function pricingInfo(Tour $tour): JsonResponse
    {
        $summary = $this->pricingService->getPricingSummary($tour);

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/tours/{tour}/date-prices",
     *     summary="Get date-specific prices for a tour",
     *     tags={"Pricing"},
     *     @OA\Parameter(
     *         name="tour",
     *         in="path",
     *         required=true,
     *         description="Tour ID or slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Start date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="End date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of date-specific prices",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="date", type="string", format="date", example="2025-12-25"),
     *                 @OA\Property(property="adult_price", type="number", example=150.00),
     *                 @OA\Property(property="child_price", type="number", example=75.00),
     *                 @OA\Property(property="infant_price", type="number", example=0.00),
     *                 @OA\Property(property="label", type="string", example="Christmas Special")
     *             ))
     *         )
     *     )
     * )
     */
    public function datePrices(Request $request, Tour $tour): JsonResponse
    {
        $query = $tour->datePrices()->enabled();

        if ($request->has('from')) {
            $query->where('date', '>=', $request->from);
        }

        if ($request->has('to')) {
            $query->where('date', '<=', $request->to);
        }

        $prices = $query->orderBy('date')->get(['date', 'adult_price', 'child_price', 'infant_price', 'label']);

        return response()->json([
            'success' => true,
            'data' => $prices,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/tours/{tour}/tiered-prices",
     *     summary="Get tiered pricing for a tour (group discounts)",
     *     tags={"Pricing"},
     *     @OA\Parameter(
     *         name="tour",
     *         in="path",
     *         required=true,
     *         description="Tour ID or slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of price tiers",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="tier_name", type="string", example="Small Group"),
     *                 @OA\Property(property="min_persons", type="integer", example=2),
     *                 @OA\Property(property="max_persons", type="integer", example=4),
     *                 @OA\Property(property="adult_price", type="number", example=90.00),
     *                 @OA\Property(property="child_price", type="number", example=45.00),
     *                 @OA\Property(property="benefits", type="string", example="Free pickup included")
     *             ))
     *         )
     *     )
     * )
     */
    public function tieredPrices(Tour $tour): JsonResponse
    {
        $tiers = $tour->tieredPrices()
            ->enabled()
            ->ordered()
            ->get(['tier_name', 'min_persons', 'max_persons', 'adult_price', 'child_price', 'infant_price', 'benefits']);

        return response()->json([
            'success' => true,
            'data' => $tiers,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/packages",
     *     summary="Get all available tour packages",
     *     tags={"Pricing"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tour packages",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Egypt Explorer Package"),
     *                 @OA\Property(property="slug", type="string", example="egypt-explorer-package"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="image", type="string"),
     *                 @OA\Property(property="package_price", type="number", example=499.00),
     *                 @OA\Property(property="original_price", type="number", example=650.00),
     *                 @OA\Property(property="savings", type="number", example=151.00),
     *                 @OA\Property(property="savings_percentage", type="number", example=23.2),
     *                 @OA\Property(property="is_available", type="boolean", example=true),
     *                 @OA\Property(property="tours", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="quantity", type="integer")
     *                 ))
     *             ))
     *         )
     *     )
     * )
     */
    public function packages(): JsonResponse
    {
        $packages = $this->pricingService->getAvailablePackages();

        $data = $packages->map(function ($package) {
            return [
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                'description' => $package->description,
                'image' => $package->image ? asset('storage/' . $package->image) : null,
                'package_price' => $package->package_price,
                'original_price' => $package->original_price,
                'savings' => $package->savings,
                'savings_percentage' => $package->savings_percentage,
                'valid_from' => $package->valid_from?->format('Y-m-d'),
                'valid_until' => $package->valid_until?->format('Y-m-d'),
                'is_available' => $package->is_available,
                'tours' => $package->items->map(fn($item) => [
                    'id' => $item->tour_id,
                    'title' => $item->tour?->title,
                    'quantity' => $item->quantity,
                    'individual_price' => $item->individual_price,
                ]),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/packages/{slug}",
     *     summary="Get tour package details",
     *     tags={"Pricing"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Package slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Package not found")
     * )
     */
    public function packageShow(string $slug): JsonResponse
    {
        $package = TourPackage::where('slug', $slug)
            ->enabled()
            ->with(['items.tour'])
            ->firstOrFail();

        if (!$package->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'This package is no longer available.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                'description' => $package->description,
                'image' => $package->image ? asset('storage/' . $package->image) : null,
                'package_price' => $package->package_price,
                'original_price' => $package->original_price,
                'savings' => $package->savings,
                'savings_percentage' => $package->savings_percentage,
                'valid_from' => $package->valid_from?->format('Y-m-d'),
                'valid_until' => $package->valid_until?->format('Y-m-d'),
                'is_available' => $package->is_available,
                'tours' => $package->items->map(fn($item) => [
                    'id' => $item->tour_id,
                    'title' => $item->tour?->title,
                    'slug' => $item->tour?->slug,
                    'featured_image' => $item->tour?->featured_image,
                    'quantity' => $item->quantity,
                    'individual_price' => $item->individual_price ?? $item->tour?->adult_price,
                ]),
            ],
        ]);
    }
}


