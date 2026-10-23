@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.faq-categories.store') }}" method="POST" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Create FAQ Category" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.faq-categories.index') }}">FAQ Categories</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="faq-categories">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'faq-categories-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'faq-categories-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]"
                                                                 id="{{$localKey}}-name" label-title="Name"/>

                                    <x-dashboard.form.input-textarea error-key="{{$localKey}}.description"
                                                                     name="{{$localKey}}[description]"
                                                                     id="{{$localKey}}-description" label-title="Description"/>
                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="icon"
                                                     name="icon" id="icon" 
                                                     label-title="Icon (CSS class)"/>

                        <x-dashboard.form.input-text error-key="order" type="number"
                                                     name="order" id="order" 
                                                     label-title="Display Order" value="0"/>

                        <x-dashboard.form.input-checkbox resource-name="FAQ Category" :value="true"
                                                         error-key="is_active"
                                                         name="is_active" id="is_active"
                                                         label-title="Active"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

