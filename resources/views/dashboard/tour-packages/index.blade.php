@extends('layouts.dashboard.app')

@section('title', 'Tour Packages')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-12">
            <div class="page-header-left d-flex justify-content-between align-items-center">
                <div>
                    <h3>Tour Packages</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item active">Tour Packages</li>
                    </ol>
                </div>
                <a href="{{ route('dashboard.tour-packages.create') }}" class="btn btn-primary">
                    <i data-feather="plus"></i> Create Package
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Tours</th>
                                <th>Package Price</th>
                                <th>Savings</th>
                                <th>Bookings</th>
                                <th>Valid Period</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                            <tr>
                                <td>
                                    @if($package->image)
                                        <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="rounded" width="60">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:60px;height:40px;">
                                            <i data-feather="image" class="text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $package->name }}</strong>
                                    <br><small class="text-muted">{{ $package->slug }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $package->items->count() }} tours</span>
                                    <br>
                                    <small class="text-muted">
                                        @foreach($package->items->take(2) as $item)
                                            {{ $item->tour?->title ?? 'N/A' }}@if(!$loop->last), @endif
                                        @endforeach
                                        @if($package->items->count() > 2)
                                            +{{ $package->items->count() - 2 }} more
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($package->package_price, 2) }}</strong>
                                    @if($package->original_price)
                                        <br><small class="text-muted text-decoration-line-through">${{ number_format($package->original_price, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($package->savings > 0)
                                        <span class="badge bg-success">Save ${{ number_format($package->savings, 2) }}</span>
                                        <br><small class="text-muted">{{ $package->savings_percentage }}% off</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $package->current_bookings }}
                                    @if($package->max_bookings)
                                        / {{ $package->max_bookings }}
                                    @endif
                                </td>
                                <td>
                                    @if($package->valid_from || $package->valid_until)
                                        {{ $package->valid_from?->format('M d, Y') ?? 'Start' }} -
                                        {{ $package->valid_until?->format('M d, Y') ?? 'Forever' }}
                                    @else
                                        Always
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $package->is_available ? 'success' : 'danger' }}">
                                        {{ $package->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.tour-packages.edit', $package) }}" class="btn btn-sm btn-info">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <form action="{{ route('dashboard.tour-packages.destroy', $package) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this package?')">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No packages created yet. <a href="{{ route('dashboard.tour-packages.create') }}">Create your first package</a>.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush

