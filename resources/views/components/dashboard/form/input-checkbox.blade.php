@props([
    'labelTitle'=> '',
    'id'=> Str::random(),
    'name',
    'value',
    'required',
    'required',
    'resourceName' => '',
    'resourceDesc' => 'Enable',
    'class'=>'',
])
<div class="form-group row align-items-center">
    <label for="{{ Str::kebab($id) }}" class="@if (!empty($class)) {{$class}} @else col-xl-3 col-md-4 @endif">{{ $labelTitle }}</label>
    <div class="@if (!empty($class)) {{$class}}  @else col-xl-6 col-md-6  @endif">
        <div class="checkbox checkbox-primary ">
            <input type="checkbox"
                   id="{{ Str::kebab($id) }}"
                   name="{{ $name }}"
                   data-original-title="{{ $labelTitle }}"
                   title="{{ $labelTitle }}"
                   @checked($value ?? old($errorKey))
                   @isset($required) required @endif >
                <label  for="{{ Str::kebab($id) }}" @if (!empty($class))
                class="arf"

                @endif>
                    @if(!empty($resourceName))
                        {{$resourceDesc}} This {{ $resourceName }}
                    @else
                        {{$resourceDesc}}
                    @endif
                </label>
        </div>
    </div>
</div>
