@if(empty($permissions) || admin()->hasRole($permissions) || admin()->hasAnyPermission($permissions))
    <li @class([
        'active-temp' => str_contains(Route::currentRouteName(), Str::of($title)->kebab()->toString())
    ])
    >
        <a class="sidebar-header" href="javascript:void(0)">
            <i data-feather="{{ $icon }}"></i>
            <span>{{ $title }}</span>
            <i class="fa fa-angle-right pull-right"></i>
        </a>

        <ul @class([
        "sidebar-submenu",
        'menu-open' => str_contains(Route::currentRouteName(), Str::of($title)->kebab()->toString())
    ])>
            @foreach($children as $child)
                <x-dashboard.sidebar.single-link :title="$child['title']" :link="$child['link']"
                                                 :permissions="$child['permissions'] ?? []"/>
            @endforeach
        </ul>
    </li>
@endif
