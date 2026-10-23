@props([
    'id' => null,
    'model'
])

<a href="javascript:;" title="Run automatic translation for this resource"
   class="btn auto-translate auto-translate-d btn-primary rounded-circle p-fixed"
   data-model="{{ $model }}" @if($id) data-id="{{ $id }}" @endif >
    <i class="fa icon fa-language"></i>
</a>
