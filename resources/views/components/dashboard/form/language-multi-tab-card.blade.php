<ul class="nav nav-tabs tab-coupon" id="{{$tabId}}" role="tablist">
    @foreach(config('translatable.supported_locales') as $localKey => $local)
        <li class="nav-item">
            <a @class([
                   'nav-link',
                   'active' => $localKey == config('app.locale'),
                   'show' => $localKey == config('app.locale'),
                ])
               id="{{$tabId. '-' . $localKey}}-tab"
               data-bs-toggle="tab" href="#{{$tabId. '-' . $localKey}}"
               role="tab" aria-controls="{{$tabId. '-' . $localKey}}"
               aria-selected="true" data-original-title="" title="">{{ $local['native'] }}</a>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="{{$tabId}}Content">
    @isset($title)
        <h4>{{ $title }}</h4>
    @endisset
    {{ $slot }}
</div>
