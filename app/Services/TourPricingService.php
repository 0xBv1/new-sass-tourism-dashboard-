<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourDatePrice;
use App\Models\TourDurationPrice;
use App\Models\TourEarlyBirdDiscount;
use App\Models\TourLastMinuteDeal;
use App\Models\TourDynamicPricing;
use App\Models\TourTieredPrice;
use App\Models\TourPackage;
use App\Models\TourPricingRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TourPricingService
{
    /**
     * Calculate the final price for a tour booking
     */
    public function calculatePrice(
        Tour $tour,
        Carbon $travelDate,
        int $adults = 1,
        int $children = 0,
        int $infants = 0,
        array $context = []
    ): array {
        $breakdown = [
            'base_adult_price' => $tour->adult_price,
            'base_child_price' => $tour->child_price ?? 0,
            'base_infant_price' => $tour->infant_price ?? 0,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'adjustments' => [],
            'final_adult_price' => $tour->adult_price,
            'final_child_price' => $tour->child_price ?? 0,
            'final_infant_price' => $tour->infant_price ?? 0,
        ];

        // 1. Check for date-specific pricing
        $datePrice = $this->getDateSpecificPrice($tour, $travelDate);
        if ($datePrice) {
            $breakdown['final_adult_price'] = $datePrice->adult_price;
            $breakdown['final_child_price'] = $datePrice->child_price;
            $breakdown['final_infant_price'] = $datePrice->infant_price;
            $breakdown['adjustments'][] = [
                'type' => 'date_specific',
                'label' => $datePrice->label ?? 'Special Date Pricing',
                'applied' => true,
            ];
        }

        // 2. Check for tiered pricing
        $totalPersons = $adults + $children;
        $tieredPrice = $this->getTieredPrice($tour, $totalPersons);
        if ($tieredPrice) {
            $breakdown['final_adult_price'] = $tieredPrice->adult_price;
            $breakdown['final_child_price'] = $tieredPrice->child_price;
            $breakdown['final_infant_price'] = $tieredPrice->infant_price;
            $breakdown['adjustments'][] = [
                'type' => 'tiered',
                'label' => $tieredPrice->tier_name,
                'applied' => true,
            ];
        }

        // 3. Check for duration-based pricing
        if (isset($context['duration_days'])) {
            $durationPrice = $this->getDurationPrice($tour, $context['duration_days']);
            if ($durationPrice) {
                $breakdown['final_adult_price'] = $durationPrice->apply($breakdown['final_adult_price']);
                $breakdown['final_child_price'] = $durationPrice->apply($breakdown['final_child_price']);
                $breakdown['adjustments'][] = [
                    'type' => 'duration',
                    'label' => "Duration pricing ({$context['duration_days']} days)",
                    'multiplier' => $durationPrice->price_multiplier,
                    'discount' => $durationPrice->fixed_discount,
                    'applied' => true,
                ];
            }
        }

        // 4. Check for early bird discounts
        $earlyBird = $this->getEarlyBirdDiscount($tour, $travelDate);
        if ($earlyBird) {
            $discountAmount = $this->calculateDiscountAmount(
                $breakdown['final_adult_price'],
                $earlyBird->discount_type,
                $earlyBird->discount_value
            );
            $breakdown['final_adult_price'] = $earlyBird->apply($breakdown['final_adult_price']);
            $breakdown['final_child_price'] = $earlyBird->apply($breakdown['final_child_price']);
            $breakdown['adjustments'][] = [
                'type' => 'early_bird',
                'label' => "Book {$earlyBird->days_before_travel}+ days early",
                'discount_type' => $earlyBird->discount_type,
                'discount_value' => $earlyBird->discount_value,
                'discount_amount' => $discountAmount,
                'applied' => true,
            ];
        }

        // 5. Check for last minute deals
        $lastMinute = $this->getLastMinuteDeal($tour, $travelDate, $context['available_slots'] ?? null);
        if ($lastMinute) {
            $discountAmount = $this->calculateDiscountAmount(
                $breakdown['final_adult_price'],
                $lastMinute->discount_type,
                $lastMinute->discount_value
            );
            $breakdown['final_adult_price'] = $lastMinute->apply($breakdown['final_adult_price']);
            $breakdown['final_child_price'] = $lastMinute->apply($breakdown['final_child_price']);
            $breakdown['adjustments'][] = [
                'type' => 'last_minute',
                'label' => "Last minute deal",
                'discount_type' => $lastMinute->discount_type,
                'discount_value' => $lastMinute->discount_value,
                'discount_amount' => $discountAmount,
                'applied' => true,
            ];
        }

        // 6. Apply dynamic pricing
        $dynamicPricing = $this->getDynamicPricingAdjustments($tour, $context);
        foreach ($dynamicPricing as $dynamic) {
            $breakdown['final_adult_price'] = $dynamic->apply($breakdown['final_adult_price']);
            $breakdown['final_child_price'] = $dynamic->apply($breakdown['final_child_price']);
            $breakdown['adjustments'][] = [
                'type' => 'dynamic',
                'label' => ucfirst(str_replace('_', ' ', $dynamic->trigger_type)),
                'action' => $dynamic->price_action,
                'adjustment_type' => $dynamic->adjustment_type,
                'adjustment_value' => $dynamic->adjustment_value,
                'applied' => true,
            ];
        }

        // 7. Apply custom pricing rules
        $rules = $this->getApplicableRules($tour, $context);
        $appliedNonStackable = false;
        
        foreach ($rules as $rule) {
            if (!$rule->stackable && $appliedNonStackable) {
                continue;
            }

            $breakdown['final_adult_price'] = $rule->apply($breakdown['final_adult_price']);
            $breakdown['final_child_price'] = $rule->apply($breakdown['final_child_price']);
            
            $breakdown['adjustments'][] = [
                'type' => 'rule',
                'label' => $rule->name,
                'rule_type' => $rule->rule_type,
                'action_type' => $rule->action_type,
                'action_value' => $rule->action_value,
                'applied' => true,
            ];

            if (!$rule->stackable) {
                $appliedNonStackable = true;
            }
        }

        // Calculate totals
        $breakdown['adult_total'] = round($breakdown['final_adult_price'] * $adults, 2);
        $breakdown['child_total'] = round($breakdown['final_child_price'] * $children, 2);
        $breakdown['infant_total'] = round($breakdown['final_infant_price'] * $infants, 2);
        $breakdown['subtotal'] = $breakdown['adult_total'] + $breakdown['child_total'] + $breakdown['infant_total'];
        
        // Calculate savings
        $originalTotal = ($tour->adult_price * $adults) + 
                        (($tour->child_price ?? 0) * $children) + 
                        (($tour->infant_price ?? 0) * $infants);
        $breakdown['savings'] = round($originalTotal - $breakdown['subtotal'], 2);
        $breakdown['savings_percentage'] = $originalTotal > 0 
            ? round(($breakdown['savings'] / $originalTotal) * 100, 1) 
            : 0;

        $breakdown['total'] = $breakdown['subtotal'];

        return $breakdown;
    }

    /**
     * Get date-specific price for a tour
     */
    public function getDateSpecificPrice(Tour $tour, Carbon $date): ?TourDatePrice
    {
        return TourDatePrice::where('tour_id', $tour->id)
            ->enabled()
            ->forDate($date->format('Y-m-d'))
            ->first();
    }

    /**
     * Get tiered price based on number of persons
     */
    public function getTieredPrice(Tour $tour, int $persons): ?TourTieredPrice
    {
        return TourTieredPrice::where('tour_id', $tour->id)
            ->enabled()
            ->forPersons($persons)
            ->ordered()
            ->first();
    }

    /**
     * Get duration-based price
     */
    public function getDurationPrice(Tour $tour, int $days): ?TourDurationPrice
    {
        return TourDurationPrice::where('tour_id', $tour->id)
            ->enabled()
            ->forDuration($days)
            ->first();
    }

    /**
     * Get applicable early bird discount
     */
    public function getEarlyBirdDiscount(Tour $tour, Carbon $travelDate): ?TourEarlyBirdDiscount
    {
        $discounts = TourEarlyBirdDiscount::where('tour_id', $tour->id)
            ->enabled()
            ->valid()
            ->orderBy('days_before_travel', 'desc')
            ->get();

        foreach ($discounts as $discount) {
            if ($discount->isApplicable($travelDate)) {
                return $discount;
            }
        }

        return null;
    }

    /**
     * Get applicable last minute deal
     */
    public function getLastMinuteDeal(Tour $tour, Carbon $travelDate, ?int $availableSlots = null): ?TourLastMinuteDeal
    {
        $deals = TourLastMinuteDeal::where('tour_id', $tour->id)
            ->enabled()
            ->valid()
            ->orderBy('discount_value', 'desc')
            ->get();

        foreach ($deals as $deal) {
            if ($deal->isApplicable($travelDate, $availableSlots)) {
                return $deal;
            }
        }

        return null;
    }

    /**
     * Get dynamic pricing adjustments
     */
    public function getDynamicPricingAdjustments(Tour $tour, array $context): Collection
    {
        $applicable = collect();

        $dynamics = TourDynamicPricing::where('tour_id', $tour->id)
            ->enabled()
            ->ordered()
            ->get();

        foreach ($dynamics as $dynamic) {
            $value = match ($dynamic->trigger_type) {
                'occupancy' => $context['occupancy_percentage'] ?? null,
                'bookings_count' => $context['bookings_count'] ?? null,
                'days_to_departure' => isset($context['travel_date']) 
                    ? Carbon::today()->diffInDays(Carbon::parse($context['travel_date']), false) 
                    : null,
                default => null,
            };

            if ($value !== null && $dynamic->isTriggered($value)) {
                $applicable->push($dynamic);
            }
        }

        return $applicable;
    }

    /**
     * Get applicable pricing rules
     */
    public function getApplicableRules(Tour $tour, array $context): Collection
    {
        return TourPricingRule::forTour($tour->id)
            ->enabled()
            ->valid()
            ->ordered()
            ->get()
            ->filter(fn($rule) => $rule->evaluate($context));
    }

    /**
     * Get all available packages
     */
    public function getAvailablePackages(): Collection
    {
        return TourPackage::enabled()
            ->valid()
            ->available()
            ->with(['items.tour'])
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Calculate discount amount
     */
    protected function calculateDiscountAmount(float $price, string $type, float $value): float
    {
        return $type === 'percentage' 
            ? round($price * $value / 100, 2)
            : $value;
    }

    /**
     * Get pricing summary for display
     */
    public function getPricingSummary(Tour $tour): array
    {
        return [
            'base_prices' => [
                'adult' => $tour->adult_price,
                'child' => $tour->child_price ?? 0,
                'infant' => $tour->infant_price ?? 0,
            ],
            'has_date_specific' => $tour->datePrices()->enabled()->exists(),
            'has_tiered_pricing' => $tour->tieredPrices()->enabled()->exists(),
            'has_duration_pricing' => $tour->durationPrices()->enabled()->exists(),
            'has_early_bird' => $tour->earlyBirdDiscounts()->enabled()->valid()->exists(),
            'has_last_minute' => $tour->lastMinuteDeals()->enabled()->valid()->exists(),
            'has_dynamic_pricing' => $tour->dynamicPricing()->enabled()->exists(),
            'has_custom_rules' => TourPricingRule::forTour($tour->id)->enabled()->valid()->exists(),
        ];
    }
}


