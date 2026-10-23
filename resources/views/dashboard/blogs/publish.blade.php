
@if($blog->status !=  \App\Enums\BlogStatus::PUBLISHED->value)
    <button data-id="{{ $blog->id }}" data-status="{{ \App\Enums\BlogStatus::PUBLISHED->value }}"
            style="text-transform: unset;padding: 5px;" class="btn btn-sm btn-blog btn-success"><i class="fa fa-paper-plane"></i> Publish</button>
@else
    <button title="Published By: {{ $blog->published_by?->name }}" data-id="{{ $blog->id }}" data-status="{{ \App\Enums\BlogStatus::DRAFTED->value }}"
            style="display:inline;text-transform: unset;padding: 5px;margin: 5px" class="btn btn-sm btn-blog btn-primary"><i class="fa fa-ban"></i> Draft </button>

    <button title="Published By: {{ $blog->published_by?->name }}" data-id="{{ $blog->id }}" data-status="{{ \App\Enums\BlogStatus::PENDING->value }}"
            style="display:inline;text-transform: unset;padding: 5px;margin: 5px" class="btn btn-sm btn-blog btn-secondary"><i class="fa fa-ban"></i> Pending </button>
@endif
