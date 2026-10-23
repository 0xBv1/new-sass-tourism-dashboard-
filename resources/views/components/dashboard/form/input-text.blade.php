@props([
    'required'=>false,
    'class'=>'',
    'id',
    'value' => null,
    'errorKey' => null,
    'labelTitle' => null,
    'name' => null,
    'disabled' => false,
    'readonly' => false,
])
<div class="form-group row">
    <label for="{{ Str::kebab($id) }}" class="col-xl-3 col-md-4">
        @if($required) <span>*</span> @endif {{ Str::title($labelTitle) }}
    </label>
    <div class="col-xl-8 col-md-7">
        <input class="form-control {{$class}}" id="{{ Str::kebab($id) }}"
               @if($required) required @endif
               type="text" name="{{ $name }}"
               @disabled($disabled)
               @readonly($readonly)
               value="{{ $value ?? old($errorKey) }}">
        @isset($errorKey)
            @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </div>
</div>
