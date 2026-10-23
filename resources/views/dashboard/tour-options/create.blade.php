@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.tour-options.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Tour Option" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.tour-options.index') }}">Tour Options</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="tour-options">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'tour-options-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'tour-options-'.$localKey }}-tab">

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]" id="{{$localKey}}-name"
                                                                 label-title="Name"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.description"
                                                                 name="{{$localKey}}[description]"
                                                                 id="{{$localKey}}-description"
                                                                 label-title="Description"/>


                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card tab2-card">
                    <div class="card-body needs-validation add-product-form">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['pricing', 'groups']"
                            tab-id="pricing-groups">
                            <div class="tab-pane fade active show"
                                 id="{{ 'pricing-groups-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'pricing-groups-0' }}-tab">

                                <x-dashboard.form.input-text error-key="adult_price" name="adult_price"
                                                             id="adult_price" label-title="Adult Price"/>

                                <x-dashboard.form.input-text error-key="child_price" name="child_price"
                                                             id="child_price" label-title="Child Price"/>

                                <x-dashboard.form.submit-button/>

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'pricing-groups-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'pricing-groups-1' }}-tab">

                                <x-dashboard.tours.tour-option-pricing-groups />

                                <div class="mt-3">
                                    <x-dashboard.form.submit-button/>
                                </div>

                            </div>
                        </x-dashboard.form.multi-tab-card>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
