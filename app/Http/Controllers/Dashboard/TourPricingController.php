<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDatePrice;
use App\Models\TourDurationPrice;
use App\Models\TourEarlyBirdDiscount;
use App\Models\TourLastMinuteDeal;
use App\Models\TourDynamicPricing;
use App\Models\TourTieredPrice;
use App\Models\TourPricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourPricingController extends Controller
{
    public function index(Tour $tour)
    {
        $tour->load([
            'datePrices' => fn($q) => $q->orderBy('date'),
            'durationPrices' => fn($q) => $q->orderBy('min_days'),
            'earlyBirdDiscounts' => fn($q) => $q->orderBy('days_before_travel', 'desc'),
            'lastMinuteDeals' => fn($q) => $q->orderBy('days_before_travel'),
            'dynamicPricing' => fn($q) => $q->orderBy('priority', 'desc'),
            'tieredPrices' => fn($q) => $q->orderBy('display_order'),
            'pricingRules' => fn($q) => $q->orderBy('priority', 'desc'),
        ]);

        return view('dashboard.tour-pricing.index', compact('tour'));
    }

    // Date-specific pricing
    public function storeDatePrice(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'adult_price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'label' => 'nullable|string|max:255',
            'enabled' => 'boolean',
        ]);

        $tour->datePrices()->updateOrCreate(
            ['date' => $validated['date']],
            $validated
        );

        return back()->with('success', 'Date-specific price saved successfully.');
    }

    public function destroyDatePrice(Tour $tour, TourDatePrice $datePrice)
    {
        $datePrice->delete();
        return back()->with('success', 'Date-specific price deleted.');
    }

    // Duration pricing
    public function storeDurationPrice(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'min_days' => 'required|integer|min:1',
            'max_days' => 'required|integer|gte:min_days',
            'price_multiplier' => 'required|numeric|min:0|max:10',
            'fixed_discount' => 'nullable|numeric|min:0',
            'enabled' => 'boolean',
        ]);

        $tour->durationPrices()->create($validated);
        return back()->with('success', 'Duration pricing saved successfully.');
    }

    public function destroyDurationPrice(Tour $tour, TourDurationPrice $durationPrice)
    {
        $durationPrice->delete();
        return back()->with('success', 'Duration pricing deleted.');
    }

    // Early bird discounts
    public function storeEarlyBird(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'days_before_travel' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'enabled' => 'boolean',
        ]);

        $tour->earlyBirdDiscounts()->create($validated);
        return back()->with('success', 'Early bird discount created successfully.');
    }

    public function destroyEarlyBird(Tour $tour, TourEarlyBirdDiscount $earlyBird)
    {
        $earlyBird->delete();
        return back()->with('success', 'Early bird discount deleted.');
    }

    // Last minute deals
    public function storeLastMinute(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'days_before_travel' => 'required|integer|min:1',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_available_slots' => 'nullable|integer|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'enabled' => 'boolean',
        ]);

        $tour->lastMinuteDeals()->create($validated);
        return back()->with('success', 'Last minute deal created successfully.');
    }

    public function destroyLastMinute(Tour $tour, TourLastMinuteDeal $lastMinute)
    {
        $lastMinute->delete();
        return back()->with('success', 'Last minute deal deleted.');
    }

    // Dynamic pricing
    public function storeDynamic(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'trigger_type' => 'required|in:occupancy,bookings_count,days_to_departure',
            'threshold_min' => 'required|integer|min:0',
            'threshold_max' => 'nullable|integer|gte:threshold_min',
            'price_action' => 'required|in:increase,decrease',
            'adjustment_type' => 'required|in:percentage,fixed',
            'adjustment_value' => 'required|numeric|min:0',
            'priority' => 'nullable|integer|min:0',
            'enabled' => 'boolean',
        ]);

        $tour->dynamicPricing()->create($validated);
        return back()->with('success', 'Dynamic pricing rule created successfully.');
    }

    public function destroyDynamic(Tour $tour, TourDynamicPricing $dynamic)
    {
        $dynamic->delete();
        return back()->with('success', 'Dynamic pricing rule deleted.');
    }

    // Tiered pricing
    public function storeTiered(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'tier_name' => 'required|string|max:255',
            'min_persons' => 'required|integer|min:1',
            'max_persons' => 'required|integer|gte:min_persons',
            'adult_price' => 'required|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'infant_price' => 'nullable|numeric|min:0',
            'benefits' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'enabled' => 'boolean',
        ]);

        $tour->tieredPrices()->create($validated);
        return back()->with('success', 'Tiered price created successfully.');
    }

    public function destroyTiered(Tour $tour, TourTieredPrice $tiered)
    {
        $tiered->delete();
        return back()->with('success', 'Tiered price deleted.');
    }

    // Custom pricing rules
    public function storeRule(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rule_type' => 'required|in:date_range,day_of_week,month,holiday,nationality,age_group,group_size,booking_channel,promo_code,custom',
            'conditions' => 'nullable|array',
            'action_type' => 'required|in:percentage,fixed,set_price',
            'action_value' => 'required|numeric',
            'priority' => 'nullable|integer|min:0',
            'stackable' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'enabled' => 'boolean',
        ]);

        $tour->pricingRules()->create($validated);
        return back()->with('success', 'Pricing rule created successfully.');
    }

    public function destroyRule(Tour $tour, TourPricingRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Pricing rule deleted.');
    }

    // Toggle enabled status for any pricing item
    public function toggleEnabled(Request $request, string $type, int $id)
    {
        $model = match ($type) {
            'date' => TourDatePrice::class,
            'duration' => TourDurationPrice::class,
            'early_bird' => TourEarlyBirdDiscount::class,
            'last_minute' => TourLastMinuteDeal::class,
            'dynamic' => TourDynamicPricing::class,
            'tiered' => TourTieredPrice::class,
            'rule' => TourPricingRule::class,
            default => abort(404),
        };

        $item = $model::findOrFail($id);
        $item->update(['enabled' => !$item->enabled]);

        return response()->json(['success' => true, 'enabled' => $item->enabled]);
    }

    // Bulk import date prices
    public function bulkImportDates(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'dates' => 'required|array',
            'dates.*.date' => 'required|date',
            'dates.*.adult_price' => 'required|numeric|min:0',
            'dates.*.child_price' => 'nullable|numeric|min:0',
            'dates.*.infant_price' => 'nullable|numeric|min:0',
            'dates.*.label' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($tour, $validated) {
            foreach ($validated['dates'] as $dateData) {
                $tour->datePrices()->updateOrCreate(
                    ['date' => $dateData['date']],
                    $dateData
                );
            }
        });

        return back()->with('success', count($validated['dates']) . ' date prices imported successfully.');
    }
}


