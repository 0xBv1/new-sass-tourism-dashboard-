@props([
    'title',
    'count'=>0,
    'icon'=>'box',
    'color'=>'secondary',
    'permission' => '',
    'link' => null
])
@if(empty($permission) || admin()->can($permission))
    <div class="col-xxl-3 col-md-6 xl-50">
    <div class="card o-hidden widget-cards">
        @if($link)
            <a href="{{ $link }}" class="text-decoration-none">
        @endif
        <div class="{{ $color }}-box card-body {{ $link ? 'cursor-pointer' : '' }}" @if($link) style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';" @endif>
            <div class="media static-top-widget align-items-center">
                <div class="icons-widgets">
                    <div class="align-self-center text-center">
                        <i data-feather="{{ $icon }}" class="font-secondary"></i>
                    </div>
                </div>
                <div class="media-body media-doller">
                    <span class="m-0">{{ $title }}</span>
                    <h3 class="mb-0">
                        <span @class(['counter'=> $count > 4])>{{ $count }}</span>
                    </h3>
                </div>
            </div>
        </div>
        @if($link)
            </a>
        @endif
    </div>
</div>
@endif
