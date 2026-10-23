@props([
        'tabs',
        'title',
        'tabId',
])
<ul class="nav nav-tabs tab-coupon" id="{{$tabId}}" role="tablist">
    @foreach($tabs as $index => $tab)
        <li class="nav-item">
            <a @class([
                   'nav-link',
                   'active' => $index == 0,
                   'show' => $index == 0,
                ])
               id="{{$tabId. '-' . $index}}-tab"
               data-bs-toggle="tab" href="#{{$tabId. '-' . $index}}"
               role="tab" aria-controls="{{$tabId. '-' . $index}}"
               aria-selected="true" data-original-title="" title="">{{ Str::headline($tab) }}</a>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="{{$tabId}}Content">
    @isset($title)
        <h4>{{ $title }}</h4>
    @endisset
    {{ $slot }}
</div>
