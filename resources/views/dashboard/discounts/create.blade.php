@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.discounts.store') }}" method="POST" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Create Discount" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.discounts.index') }}">Discounts</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Discount Details</h5>
                        </div>
                        <div class="card-body">
                            <x-dashboard.form.input-text error-key="name"
                                                         name="name" id="name" 
                                                         label-title="Name" required="true"/>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select" required>
                                            @foreach($types as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="value" type="number" step="0.01"
                                                                 name="value" id="value" 
                                                                 label-title="Value" required="true"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="start_date" type="date"
                                                                 name="start_date" id="start_date" 
                                                                 label-title="Start Date (Optional)"/>
                                </div>
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="end_date" type="date"
                                                                 name="end_date" id="end_date" 
                                                                 label-title="End Date (Optional)"/>
                                </div>
                            </div>

                            <x-dashboard.form.input-text error-key="priority" type="number"
                                                         name="priority" id="priority" 
                                                         label-title="Priority" value="0"/>

                            <x-dashboard.form.input-textarea error-key="description"
                                                             name="description" id="description" 
                                                             label-title="Description"/>

                            <x-dashboard.form.input-checkbox resource-name="Discount" :value="true"
                                                             error-key="is_active"
                                                             name="is_active" id="is_active"
                                                             label-title="Active"/>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Apply to Tours</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @foreach($tours as $tour)
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" 
                                           name="tours[]" value="{{ $tour->id }}" 
                                           id="tour-{{ $tour->id }}">
                                    <label class="form-check-label" for="tour-{{ $tour->id }}">
                                        {{ $tour->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <x-dashboard.form.submit-button/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

