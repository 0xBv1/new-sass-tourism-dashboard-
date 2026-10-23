@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.group-sizes.update' , $groupSize) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Group Size" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.group-sizes.index') }}">Group Sizes</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="group-sizes">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'group-sizes-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'group-sizes-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]"
                                                                 :value="$groupSize->translateOrNew($localKey)->name"
                                                                 id="{{$localKey}}-name" label-title="Name"/>
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
                                                     :value="$groupSize->slug"
                                                     label-title="Slug"/>

                        <x-dashboard.form.input-text error-key="min_people"
                                                     name="min_people" id="min_people"
                                                     :value="$groupSize->min_people"
                                                     label-title="Min People"/>

                        <x-dashboard.form.input-text error-key="max_people"
                                                     name="max_people" id="max_people"
                                                     :value="$groupSize->max_people"
                                                     label-title="Max People"/>

                        <x-dashboard.form.input-text error-key="display_order"
                                                     name="display_order"
                                                     :value="$groupSize->display_order"
                                                     id="display_order"
                                                     label-title="Display Order"/>

                        <x-dashboard.form.input-checkbox resource-name="Group Size"
                                                         error-key="enabled"
                                                         :value="$groupSize->enabled"
                                                         name="enabled" id="enabled"
                                                         label-title="Enabled"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
    <x-dashboard.partials.resource-translation model="GroupSize" :id="$groupSize->id"/>
@endsection
