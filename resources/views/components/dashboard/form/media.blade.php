@props([
    'multiple' => false,
    'images' => [],
    'name',
    'title',
])
<a href="javascript:;"
   data-target="#{{ Str::of($name)->remove('[')->remove(']') }}"
   data-name="{{ $name }}"
   @if($multiple)
       multiple=""
   @endif
   class="open-media text-center btn btn-outline-primary w-100">
    <i class="fa fa-plus"></i> {{ $title }}
</a>

<div class="gallery" id="{{ Str::of($name)->remove('[')->remove(']') }}">
    <div class="title">
        <i class="fa-2x text-danger text-center d-block fa fa-cloud-upload"></i>
        <h3>{{ $title }}</h3>
    </div>
    @foreach(array_filter((array) $images) as $image)
        <div class="card image-box m-5">
            <input type="hidden" name="{{ $name }}" value="{{ $image }}">
            <img src="{{ $image }}" class="card-img-top" alt="...">
            <a href="javascript:;" class="btn btn-remove btn-danger btn-sm"><i class="fa fa-trash"></i></a>
        </div>
    @endforeach
</div>
