<a href="{{ route('dashboard.appointments.show', $id) }}" class="btn btn-sm btn-info">
    <i class="fa fa-eye"></i>
</a>

<a data-delete-url="{{ route('dashboard.appointments.destroy', $id) }}" href="javascript:;"
   type="button" class="btn btn-sm btn-danger btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a>

