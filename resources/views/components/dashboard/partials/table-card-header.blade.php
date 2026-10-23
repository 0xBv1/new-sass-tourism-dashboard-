<div class="card-header">
    <form class="form-inline search-form search-box">
        <div class="form-group">
            <input id="datatable-search" aria-label="Search" class="form-control" type="search"
                   placeholder="Search..">
        </div>
    </form>
    <div>
        @if(\Illuminate\Support\Facades\Route::has('dashboard.'.$model->plural()->lower()->kebab().'.create') &&
        admin()->can($model->plural()->lower()->kebab().'.create'))
            <a href="{{ route('dashboard.'.$model->plural()->lower()->kebab().'.create') }}" type="button"
               class="btn btn-primary add-row mt-md-0 mt-2">
               <i class="fa fa-plus"></i> Add {{$model->plural()->headline()}}
            </a>
        @endif
        {{ $slot }}
    </div>
</div>
