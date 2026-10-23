@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.amenities.update' , $amenity) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Amenity" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.amenities.index') }}">Amenities</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="amenities">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'amenities-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'amenities-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]"
                                                                 :value="$amenity->translateOrNew($localKey)->name"
                                                                 id="{{$localKey}}-name" label-title="Name"/>


                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="icon_name" name="icon_name" :value="$amenity->icon_name"
                                                     id="icon_name" label-title="Icon Name"/>


                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
