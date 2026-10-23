<div class="btn-group" role="group">
    <a href="{{ route('dashboard.trips.show', $trip) }}" class="btn btn-sm btn-info" title="View">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('dashboard.trips.edit', $trip) }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
    @can('trip-bookings.create')
        <a href="{{ route('dashboard.trip-bookings.create') }}?trip_id={{ $trip->id }}" class="btn btn-sm btn-success" title="Create Booking">
            <i class="fa fa-plus"></i>
        </a>
    @endcan
    <a href="{{ route('dashboard.trips.trip-bookings', $trip) }}" class="btn btn-sm btn-warning" title="View Bookings">
        <i class="fa fa-list"></i>
    </a>
    <form action="{{ route('dashboard.trips.destroy', $trip) }}" method="POST" class="d-inline" 
          onsubmit="return confirm('Are you sure you want to delete this trip?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</div> 