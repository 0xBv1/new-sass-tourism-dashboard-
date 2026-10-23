<a title="Add Room" href="{{ route('dashboard.rooms.create', ['hotel_id' => $id]) }}">
    <i class="fa fa-plus"></i>
</a>

<a href="{{ route('dashboard.hotels.edit', $id) }}">
    <i class="fa fa-edit"></i>
</a>

<a data-delete-url="{{ route('dashboard.hotels.destroy', $id) }}" href="javascript:;"
   type="button" class="btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a>
