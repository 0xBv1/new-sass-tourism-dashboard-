@props([
    'tour' => null,
    'relations' => [],
])

@php
    $seasons = [
        "From Jan " . now()->year ." to Apr " . now()->year,
        "From May " . now()->year ." to Sep " . now()->year,
        "From Oct " . now()->year ." to Dec " . now()->year,
        "Peak (23 Dec ". now()->year ." - 7 Jan ".(now()->year + 1).") / (14 - 23 Apr ". now()->year .")"
    ];
    
    $accommodationCategories = $relations['accommodation_categories'] ?? collect();
    $groupSizes = $relations['group_sizes'] ?? collect();
    $hotels = $relations['hotels'] ?? collect();
@endphp

<x-dashboard.form.multi-tab-card :tabs="['jan-apr', 'may-sep', 'oct-dec', 'peak']"
                                 tab-id="season_price">
    @for($i = 0 ; $i < 4 ; $i++)
        @php
            $current_season = $tour && isset($tour->seasons[$i]) ? $tour->seasons[$i]->available ?? [] : [];
        @endphp
        <div class="tab-pane fade @if($i == 0) active show @endif"
             id="{{ 'season_price-' . $i }}" role="tabpanel"
             aria-labelledby="{{ 'season_price-' . $i  }}-tab">
            
            {{-- Accommodation Section --}}
            <h3 class="text-center my-2">{{ $seasons[$i]  }} [Accommodation]</h3>
            <hr>
            <input type="hidden" name="seasons[{{$i}}][season_name]" value="From Jan to Apr [Accommodation]">

            @foreach($accommodationCategories as $category)
                <h4>{{ $category->name }}</h4>
                
                <x-dashboard.form.input-select 
                    name="seasons[{{$i}}][accommodation][{{$category->slug}}][hotels][]" 
                    multible 
                    :options="$hotels" 
                    track-by="id"
                    option-lable="name" 
                    label-title="Select Hotels for {{ $category->name }}" 
                    id="season-{{$i}}-accommodation-{{$category->slug}}-hotels"
                    :value="$current_season['accommodation'][$category->slug]['hotels'] ?? []"
                    error-key="seasons.{{$i}}.accommodation.{{$category->slug}}.hotels" />
                
                @foreach($groupSizes as $groupSize)
                    <x-dashboard.form.input-text 
                        error-key="" 
                        id="{{'pricing' . rand(1111,9999)}}"
                        name="seasons[{{$i}}][accommodation][{{$category->slug}}][{{$groupSize->slug}}]"
                        :value="$current_season['accommodation'][$category->slug][$groupSize->slug] ?? ''"
                        label-title="{{ $category->name }} {{ $groupSize->name }}" />
                @endforeach
                <hr>
            @endforeach
        </div>
    @endfor
</x-dashboard.form.multi-tab-card>
