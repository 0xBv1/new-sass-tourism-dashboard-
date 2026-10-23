@extends('layouts.dashboard.app')

@section('title', 'Tour Pricing - ' . $tour->title)

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-12">
            <div class="page-header-left">
                <h3>Advanced Pricing for: {{ $tour->title }}</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.tours.index') }}">Tours</a></li>
                    <li class="breadcrumb-item active">Pricing</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <!-- Base Prices Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i data-feather="dollar-sign" class="me-2"></i> Base Prices</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Adult Price</h6>
                            <h3 class="text-primary">${{ number_format($tour->adult_price, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Child Price</h6>
                            <h3 class="text-success">${{ number_format($tour->child_price ?? 0, 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Infant Price</h6>
                            <h3 class="text-info">${{ number_format($tour->infant_price ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs nav-primary" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#date-specific">
                    <i data-feather="calendar"></i> Date-Specific
                    <span class="badge bg-secondary ms-1">{{ $tour->datePrices->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tiered">
                    <i data-feather="layers"></i> Tiered
                    <span class="badge bg-secondary ms-1">{{ $tour->tieredPrices->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#duration">
                    <i data-feather="clock"></i> Duration
                    <span class="badge bg-secondary ms-1">{{ $tour->durationPrices->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#early-bird">
                    <i data-feather="sunrise"></i> Early Bird
                    <span class="badge bg-secondary ms-1">{{ $tour->earlyBirdDiscounts->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#last-minute">
                    <i data-feather="zap"></i> Last Minute
                    <span class="badge bg-secondary ms-1">{{ $tour->lastMinuteDeals->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#dynamic">
                    <i data-feather="trending-up"></i> Dynamic
                    <span class="badge bg-secondary ms-1">{{ $tour->dynamicPricing->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#rules">
                    <i data-feather="sliders"></i> Custom Rules
                    <span class="badge bg-secondary ms-1">{{ $tour->pricingRules->count() }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Date-Specific Pricing Tab -->
            <div class="tab-pane fade show active" id="date-specific" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Date-Specific Prices</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDatePriceModal">
                            <i data-feather="plus"></i> Add Date Price
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Set custom prices for specific dates (holidays, peak seasons, special events).</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Label</th>
                                        <th>Adult Price</th>
                                        <th>Child Price</th>
                                        <th>Infant Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->datePrices as $datePrice)
                                    <tr>
                                        <td>{{ $datePrice->date->format('M d, Y') }}</td>
                                        <td>{{ $datePrice->label ?? '-' }}</td>
                                        <td>${{ number_format($datePrice->adult_price, 2) }}</td>
                                        <td>${{ number_format($datePrice->child_price, 2) }}</td>
                                        <td>${{ number_format($datePrice->infant_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $datePrice->enabled ? 'success' : 'danger' }}">
                                                {{ $datePrice->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.date.destroy', [$tour, $datePrice]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this price?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No date-specific prices configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiered Pricing Tab -->
            <div class="tab-pane fade" id="tiered" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tiered Pricing (Group Sizes)</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTieredModal">
                            <i data-feather="plus"></i> Add Tier
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Set different prices based on group size (e.g., Solo, Small Group, Large Group).</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tier Name</th>
                                        <th>Persons Range</th>
                                        <th>Adult Price</th>
                                        <th>Child Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->tieredPrices as $tier)
                                    <tr>
                                        <td><strong>{{ $tier->tier_name }}</strong></td>
                                        <td>{{ $tier->min_persons }} - {{ $tier->max_persons }} persons</td>
                                        <td>${{ number_format($tier->adult_price, 2) }}</td>
                                        <td>${{ number_format($tier->child_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $tier->enabled ? 'success' : 'danger' }}">
                                                {{ $tier->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.tiered.destroy', [$tour, $tier]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this tier?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No tiered pricing configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration Pricing Tab -->
            <div class="tab-pane fade" id="duration" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Duration-Based Pricing</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDurationModal">
                            <i data-feather="plus"></i> Add Duration Rule
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Adjust prices based on trip duration (apply multiplier or fixed discount).</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Duration Range</th>
                                        <th>Price Multiplier</th>
                                        <th>Fixed Discount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->durationPrices as $duration)
                                    <tr>
                                        <td>{{ $duration->min_days }} - {{ $duration->max_days }} days</td>
                                        <td>{{ number_format($duration->price_multiplier, 2) }}x</td>
                                        <td>${{ number_format($duration->fixed_discount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $duration->enabled ? 'success' : 'danger' }}">
                                                {{ $duration->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.duration.destroy', [$tour, $duration]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No duration pricing configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Early Bird Tab -->
            <div class="tab-pane fade" id="early-bird" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Early Bird Discounts</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEarlyBirdModal">
                            <i data-feather="plus"></i> Add Early Bird
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Reward customers who book in advance with special discounts.</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Days Before Travel</th>
                                        <th>Discount</th>
                                        <th>Valid Period</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->earlyBirdDiscounts as $earlyBird)
                                    <tr>
                                        <td>{{ $earlyBird->days_before_travel }}+ days</td>
                                        <td>
                                            @if($earlyBird->discount_type === 'percentage')
                                                {{ $earlyBird->discount_value }}% off
                                            @else
                                                ${{ number_format($earlyBird->discount_value, 2) }} off
                                            @endif
                                        </td>
                                        <td>
                                            @if($earlyBird->valid_from || $earlyBird->valid_until)
                                                {{ $earlyBird->valid_from?->format('M d, Y') ?? 'Start' }} - 
                                                {{ $earlyBird->valid_until?->format('M d, Y') ?? 'Forever' }}
                                            @else
                                                Always
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $earlyBird->enabled ? 'success' : 'danger' }}">
                                                {{ $earlyBird->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.early-bird.destroy', [$tour, $earlyBird]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No early bird discounts configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Minute Tab -->
            <div class="tab-pane fade" id="last-minute" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Last Minute Deals</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLastMinuteModal">
                            <i data-feather="plus"></i> Add Deal
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Offer discounts for bookings made close to the travel date.</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Within Days</th>
                                        <th>Discount</th>
                                        <th>Min. Slots Available</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->lastMinuteDeals as $deal)
                                    <tr>
                                        <td>Within {{ $deal->days_before_travel }} days</td>
                                        <td>
                                            @if($deal->discount_type === 'percentage')
                                                {{ $deal->discount_value }}% off
                                            @else
                                                ${{ number_format($deal->discount_value, 2) }} off
                                            @endif
                                        </td>
                                        <td>{{ $deal->min_available_slots ?? 'Any' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $deal->enabled ? 'success' : 'danger' }}">
                                                {{ $deal->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.last-minute.destroy', [$tour, $deal]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No last minute deals configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Pricing Tab -->
            <div class="tab-pane fade" id="dynamic" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dynamic Pricing Rules</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDynamicModal">
                            <i data-feather="plus"></i> Add Dynamic Rule
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Automatically adjust prices based on demand (occupancy, bookings, time to departure).</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Trigger</th>
                                        <th>Threshold</th>
                                        <th>Action</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->dynamicPricing as $dynamic)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $dynamic->trigger_type)) }}</td>
                                        <td>{{ $dynamic->threshold_min }}{{ $dynamic->threshold_max ? ' - ' . $dynamic->threshold_max : '+' }}%</td>
                                        <td>
                                            <span class="badge bg-{{ $dynamic->price_action === 'increase' ? 'warning' : 'info' }}">
                                                {{ ucfirst($dynamic->price_action) }}
                                            </span>
                                            {{ $dynamic->adjustment_value }}{{ $dynamic->adjustment_type === 'percentage' ? '%' : '$' }}
                                        </td>
                                        <td>{{ $dynamic->priority }}</td>
                                        <td>
                                            <span class="badge bg-{{ $dynamic->enabled ? 'success' : 'danger' }}">
                                                {{ $dynamic->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.dynamic.destroy', [$tour, $dynamic]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No dynamic pricing rules configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Rules Tab -->
            <div class="tab-pane fade" id="rules" role="tabpanel">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Custom Pricing Rules</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                            <i data-feather="plus"></i> Add Rule
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Create flexible pricing rules based on various conditions.</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                        <th>Priority</th>
                                        <th>Stackable</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tour->pricingRules as $rule)
                                    <tr>
                                        <td><strong>{{ $rule->name }}</strong></td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $rule->rule_type)) }}</td>
                                        <td>
                                            @if($rule->action_type === 'set_price')
                                                Set to ${{ number_format($rule->action_value, 2) }}
                                            @else
                                                {{ $rule->action_value }}{{ $rule->action_type === 'percentage' ? '%' : '$' }} off
                                            @endif
                                        </td>
                                        <td>{{ $rule->priority }}</td>
                                        <td>
                                            <span class="badge bg-{{ $rule->stackable ? 'success' : 'secondary' }}">
                                                {{ $rule->stackable ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $rule->enabled ? 'success' : 'danger' }}">
                                                {{ $rule->enabled ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('dashboard.tour-pricing.rule.destroy', [$tour, $rule]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No custom pricing rules configured.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('dashboard.tour-pricing.modals.date-price')
@include('dashboard.tour-pricing.modals.tiered')
@include('dashboard.tour-pricing.modals.duration')
@include('dashboard.tour-pricing.modals.early-bird')
@include('dashboard.tour-pricing.modals.last-minute')
@include('dashboard.tour-pricing.modals.dynamic')
@include('dashboard.tour-pricing.modals.rule')

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush

