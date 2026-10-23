<a title="View Booking" href="{{ route('dashboard.trip-bookings.show', $id) }}">
    <i class="fa fa-eye"></i>
</a>

<a title="Edit Booking" href="{{ route('dashboard.trip-bookings.edit', $id) }}">
    <i class="fa fa-edit"></i>
</a>

<a data-delete-url="{{ route('dashboard.trip-bookings.destroy', $id) }}" href="javascript:;"
   type="button" class="btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a> 