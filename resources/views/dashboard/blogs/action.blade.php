<a target="_blank" title="Visit On Site" href="{{ $model->site_url }}">
    <i class="fa fa-globe"></i>
</a>

<a href="{{ route('dashboard.blogs.edit', $id) }}">
    <i class="fa fa-edit"></i>
</a>

<a data-delete-url="{{ route('dashboard.blogs.destroy', $id) }}" href="javascript:;"
   type="button" class="btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a>
