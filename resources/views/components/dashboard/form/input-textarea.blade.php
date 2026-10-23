<div class="form-group row">
    <label for="{{ Str::kebab($id) }}" class="col-xl-3 col-md-4">
        @isset($required) <span>*</span> @endif
        {{ $labelTitle }}
    </label>
    <div class="col-xl-8 col-md-7">
        <textarea class="form-control ml-0" id="{{ Str::kebab($id) }}"
                  @isset($required) required @endif
                  type="text" name="{{ $name }}"
                  cols="30" rows="10">{{ $value ?? old($errorKey) }}</textarea>
        @isset($errorKey)
            @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </div>
</div>
