<div class="form-group row">
    <label class="col-xl-3 col-md-4" for="{{ $id }}">{{ $labelTitle }}</label>
    <div class="col-xl-8 col-md-7">
        <input
            type="date"
            class="form-control"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name, $value ?? '') }}"
            @isset($required) required @endisset
        >

        @isset($errorKey)
            @error($errorKey)
            <span class="d-block text-danger">{{ $message }}</span>
            @enderror
        @endisset
    </div>
</div>
