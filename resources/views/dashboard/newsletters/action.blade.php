@if($status === 'draft')
    <a href="{{ route('dashboard.newsletters.edit', $id) }}" class="btn btn-sm btn-primary">
        <i class="fa fa-edit"></i>
    </a>
    <form action="{{ route('dashboard.newsletters.send', $id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Send this newsletter to all subscribers?')">
            <i class="fa fa-paper-plane"></i>
        </button>
    </form>
@endif

<a href="{{ route('dashboard.newsletters.preview', $id) }}" target="_blank" class="btn btn-sm btn-info">
    <i class="fa fa-eye"></i>
</a>

@if($status !== 'sending')
    <a data-delete-url="{{ route('dashboard.newsletters.destroy', $id) }}" href="javascript:;"
       type="button" class="btn btn-sm btn-danger btn-delete-resource-modal" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
        <i class="fa fa-trash"></i>
    </a>
@endif

