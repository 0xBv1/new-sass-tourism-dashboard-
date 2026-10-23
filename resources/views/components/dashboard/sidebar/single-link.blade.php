@props([
        'class'=> null,
        'permissions'=> null,
        'link'=> null,
        'title'=> null,
        'icon'=> null,
])
@if(empty($permissions) || admin()->hasRole($permissions) || admin()->hasAnyPermission($permissions))
    <li>
        <a @class(['sidebar-header', $class]) href="{{ $link }}">
            @if($icon)
                <i data-feather="{{ $icon }}"></i>
            @else
                <i class="fa fa-circle"></i>
            @endif
            <span>{{ $title }}</span>
        </a>
    </li>
@endif
