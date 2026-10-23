<a href="{{ route('dashboard.accommodations.edit', $id) }}" class="btn btn-sm btn-primary">
    <i class="fa fa-edit"></i>
</a>
<form action="{{ route('dashboard.accommodations.destroy', $id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('Are you sure you want to delete this accommodation?')">
        <i class="fa fa-trash"></i>
    </button>
</form>
