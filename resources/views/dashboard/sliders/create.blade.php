@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.sliders.store') }}" method="POST" enctype="multipart/form-data" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Create Slider" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.sliders.index') }}">Sliders</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card">
                    <div class="card-header">
                        <h5>Slider Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-dashboard.form.input-text error-key="name"
                                                             name="name" id="name" 
                                                             label-title="Name" required="true"/>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Key</label>
                                    <select name="key" class="form-select" required id="slider-key">
                                        <option value="">Select or type custom key</option>
                                        @foreach($commonKeys as $value => $label)
                                            <option value="{{ $value }}">{{ $label }} ({{ $value }})</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control mt-2" id="custom-key" 
                                           placeholder="Or enter custom key" style="display:none;">
                                </div>
                            </div>
                        </div>

                        <x-dashboard.form.input-textarea error-key="description"
                                                         name="description" id="description" 
                                                         label-title="Description"/>

                        <x-dashboard.form.input-checkbox resource-name="Slider" :value="true"
                                                         error-key="is_active"
                                                         name="is_active" id="is_active"
                                                         label-title="Active"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('slider-key').addEventListener('change', function() {
            var customInput = document.getElementById('custom-key');
            if (this.value === '') {
                customInput.style.display = 'block';
                customInput.name = 'key';
                this.name = '';
            } else {
                customInput.style.display = 'none';
                customInput.name = '';
                this.name = 'key';
            }
        });
    </script>
@endsection

