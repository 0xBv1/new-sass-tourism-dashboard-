@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.faq-categories.update', $faqCategory) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <x-dashboard.partials.breadcrumb title="Edit FAQ Category" :hideFirst="true">
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
                                                                 id="{{$localKey}}-name" label-title="Name"
                                                                 :value="$faqCategory->translate($localKey)?->name"/>

                                    <x-dashboard.form.input-textarea error-key="{{$localKey}}.description"
                                                                     name="{{$localKey}}[description]"
                                                                     id="{{$localKey}}-description" label-title="Description"
                                                                     :value="$faqCategory->translate($localKey)?->description"/>
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
                                                     label-title="Icon (CSS class)"
                                                     :value="$faqCategory->icon"/>

                        <x-dashboard.form.input-text error-key="order" type="number"
                                                     name="order" id="order" 
                                                     label-title="Display Order"
                                                     :value="$faqCategory->order"/>

                        <x-dashboard.form.input-checkbox resource-name="FAQ Category" :value="$faqCategory->is_active"
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

