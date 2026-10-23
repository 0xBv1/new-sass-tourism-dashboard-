<a title="View Trip Details" href="{{ route('dashboard.trips.show', $id) }}">
    <i class="fa fa-eye"></i>
</a>

<a title="View Bookings" href="{{ route('dashboard.trips.trip-bookings', $id) }}">
    <i class="fa fa-list"></i>
</a>

<a title="Edit Trip" href="{{ route('dashboard.trips.edit', $id) }}">
    <i class="fa fa-edit"></i>
</a>

<a data-delete-url="{{ route('dashboard.trips.destroy', $id) }}" href="javascript:;"
   type="button" class="btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a> 