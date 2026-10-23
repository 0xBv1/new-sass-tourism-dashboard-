@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Currencies">
            <li class="breadcrumb-item active">Currencies</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="currency">
                            <a href="javascript:;" type="button"
                               class="btn btn-success add-row mt-md-0 mt-2 update-rates">
                                Update Rates
                            </a>
                        </x-dashboard.partials.table-card-header>
                        <div class="card-body order-datatable overflow-x-auto">
                            <div class="">
                                {!! $dataTable->table(['class'=>'display']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection

@push('js')
    <script>
        $('.update-rates').click(function () {
            $(this).attr('disabled', true)
            axios.get("{{ route('dashboard.currencies.rates.update') }}")
                .then(res=> {
                    toastr.success(res.data.message)
                }).catch(error=> {
                    toastr.error(error.response.data.message || "Unexpected Error")
                })
                .finally(()=>{
                $(this).attr('disabled', false)
                $('#data-table').DataTable().draw()
            })
        })
    </script>
@endpush
