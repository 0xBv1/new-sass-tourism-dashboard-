@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Car Routes">
            <li class="breadcrumb-item active">Car Routes</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="car-route">
                            <a data-model="Destination" data-toggle="modal" data-target="#importRoutes"
                               type="button" class="btn btn-info text-white add-row mt-md-0 mt-2">
                                <i class="fa icon fa-cloud-upload"></i> Import Routes
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

    <div class="modal fade" id="importRoutes" tabindex="-1" aria-labelledby="importRoutesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importRoutesLabel">Upload CSV File</h5>
                </div>
                <div class="modal-body">
                    Download Template <a href="{{ route('dashboard.car-routes.template') }}">Click Here.</a>
                    <form id="importRoutesForm" enctype="multipart/form-data"
                          action="{{ route('dashboard.car-routes.import') }}" method="POST" class="mt-5">
                        @csrf
                        <input type="file"  class="mt-5 mb-2 form-control" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary importing-buttons" data-dismiss="modal">Cancel</button>
                    <button form="importRoutesForm" type="submit" class="btn importing-buttons btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $('#importRoutesForm').on('submit', function () {
            $('.importing-buttons').attr('disabled', true)
        })
    </script>
@endpush
