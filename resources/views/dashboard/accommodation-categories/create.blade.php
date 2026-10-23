@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.accommodation-categories.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Accommodation Category" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.accommodation-categories.index') }}">Accommodation Categories</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="accommodation-categories">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'accommodation-categories-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'accommodation-categories-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]" id="{{$localKey}}-name"
                                                                 label-title="Name"/>
                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card tab2-card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="slug"
                                                     name="slug" id="slug"
                                                     label-title="Slug"/>

                        <x-dashboard.form.input-text error-key="display_order"
                                                     name="display_order"
                                                     value="0"
                                                     id="display_order"
                                                     label-title="Display Order"/>

                        <x-dashboard.form.input-checkbox resource-name="Accommodation Category" :value="true"
                                                         error-key="enabled"
                                                         name="enabled" id="enabled"
                                                         label-title="Enabled"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
