@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="RedirectRules">
            <li class="breadcrumb-item active">RedirectRules</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert/>
                    <div class="card">
                        <x-dashboard.partials.table-card-header model="redirect-rule">
                            @can('redirect-rules.export')
                                <a href="{{ route('dashboard.redirect-rules.export') }}"
                                   type="button" class=" btn btn-success add-row mt-md-0 mt-2">
                                    <i class="fa icon fa-download"></i> Export Redirect Rules JS File
                                </a>
                            @endcan
                            @can('redirect-rules.import')
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#importModal"
                                   type="button" class="text-white btn btn-info add-row mt-md-0 mt-2">
                                    <i class="fa icon fa-upload"></i> Import Redirect Rules
                                </a>
                            @endcan
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
    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Redirect Rule From File</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Please upload a valid csv file for redirect rules, for the template file
                        <a target="_blank" class="text-bold" href="{{ asset('assets/admin/redirect-rules.csv') }}">Click Here</a>
                    </div>
                    <form id="importForm" enctype="multipart/form-data" action="{{ route('dashboard.redirect-rules.import') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="file">Select File</label>
                            <input required type="file" class="form-control-file" id="file" name="file" accept=".csv,.txt">
                            <small class="form-text text-muted d-block">Please select a valid csv file.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                       <i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary" form="importForm">
                        <i class="fa fa-upload"></i> Import</button>
                </div>
            </div>
        </div>
    </div>

@endsection
