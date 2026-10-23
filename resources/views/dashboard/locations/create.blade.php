@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.locations.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Location" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.locations.index') }}">Locations</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert />

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="locations">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'locations-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'locations-'.$localKey }}-tab">
                                     <x-dashboard.form.input-text error-key="{{$localKey}}.name" name="{{$localKey}}[name]"  id="{{$localKey}}-name" label-title="Name"/>
                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <x-dashboard.form.input-checkbox resource-name="Location" :value="true"
                                                         error-key="active"
                                                         name="active" id="active"
                                                         label-title="Active"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
