@props([
    'value' => 0,
    'id' ,
    'labelTitle' ,
    'name' ,
    'errorKey' ,
])
<div class="form-group row">
    <label for="{{ Str::kebab($id) }}" class="col-xl-3 col-sm-4 mb-0">{{ $labelTitle }}</label>
    <fieldset class="qty-box col-xl-9 col-xl-8 col-sm-7">
        <div class="input-group">
            <input name="{{ $name }}" id="{{ Str::kebab($id) }}" class="touchspin" type="text"
                   value="{{ old($name, $value) }}">
        </div>
        @isset($errorKey)
            @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </fieldset>
</div>
