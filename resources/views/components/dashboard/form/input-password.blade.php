<div class="form-group row">
    <label for="{{ Str::kebab($id) }}" class="col-xl-3 col-md-4">
        @isset($required) <span>*</span> @endif {{ $labelTitle }}
    </label>
    <div class="col-xl-8 col-md-7">
        <input class="form-control @isset($class) {{$class}} @endisset " id="{{ Str::kebab($id) }}"
               @isset($required) required @endif
               autocomplete="new-password"
               type="password" name="{{ $name }}">
        @isset($errorKey)
            @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </div>
</div>
