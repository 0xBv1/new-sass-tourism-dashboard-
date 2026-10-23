<div class="d-flex gap-2 justify-content-center">
    <a href="{{ route('dashboard.blog-comments.edit', $id) }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
    
    @if(!$parent_id)
    <a href="{{ route('dashboard.blog-comments.create-reply', $id) }}" class="btn btn-sm btn-secondary" title="Add Reply">
        <i class="fa fa-reply"></i>
    </a>
    @endif
    
    @if(!$is_approved)
        <form action="{{ route('dashboard.blog-comments.approve', $id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Are you sure you want to approve this comment?')">
                <i class="fa fa-check"></i>
            </button>
        </form>
    @else
        <form action="{{ route('dashboard.blog-comments.reject', $id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning" title="Reject" onclick="return confirm('Are you sure you want to reject this comment?')">
                <i class="fa fa-times"></i>
            </button>
        </form>
    @endif

    <form action="{{ route('dashboard.blog-comments.toggle-featured', $id) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-info" title="{{ $is_featured ? 'Unfeature' : 'Feature' }}">
            <i class="fa fa-star{{ $is_featured ? '' : '-o' }}"></i>
        </button>
    </form>

    <form action="{{ route('dashboard.blog-comments.destroy', $id) }}" method="POST" class="d-inline" 
          onsubmit="return confirm('Are you sure you want to delete this comment?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</div>
