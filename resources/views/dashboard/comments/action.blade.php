@if(!$is_approved)
    <form action="{{ route('dashboard.comments.approve', $id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-success" title="Approve">
            <i class="fa fa-check"></i>
        </button>
    </form>
@else
    <form action="{{ route('dashboard.comments.reject', $id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-warning" title="Reject">
            <i class="fa fa-times"></i>
        </button>
    </form>
@endif

<form action="{{ route('dashboard.comments.toggle-featured', $id) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-sm btn-{{ $is_featured ? 'primary' : 'outline-primary' }}" title="Toggle Featured">
        <i class="fa fa-star"></i>
    </button>
</form>

<a data-delete-url="{{ route('dashboard.comments.destroy', $id) }}" href="javascript:;"
   type="button" class="btn btn-sm btn-danger btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
    <i class="fa fa-trash"></i>
</a>

