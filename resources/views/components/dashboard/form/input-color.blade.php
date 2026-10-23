<div class="form-group row">
    <label for="{{ Str::kebab($id) }}" class="col-xl-3 col-md-4">
        @isset($required) <span>*</span> @endif {{ $labelTitle }}
    </label>
    <div class="col-xl-8 col-md-7 color-box">
        <input class=" @isset($class) {{$class}} @endisset " id="{{ Str::kebab($id) }}"
               @isset($required) required @endif
               type="color" name="{{ $name }}"
               value="{{ $value ?? old($name, '#000') }}">
        <span style="background-color: {{ $value ?? old($name, '#000') }}"></span>
        @isset($errorKey)
            @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </div>
</div>
