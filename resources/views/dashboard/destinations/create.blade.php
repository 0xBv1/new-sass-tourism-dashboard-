@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.destinations.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Destination" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.destinations.index') }}">Destinations</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="destinations">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'destinations-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'destinations-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.title"
                                                                 name="{{$localKey}}[title]" id="{{$localKey}}-title"
                                                                 label-title="Title"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.description"
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
                    <div class="card-body">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['featured', 'media']"
                            tab-id="featured-media">
                            <div class="tab-pane fade active show"
                                 id="{{ 'featured-media-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-0' }}-tab">

                                <x-dashboard.form.input-text error-key="slug"
                                                             name="slug"
                                                             id="slug"
                                                             label-title="Slug"/>

                                <x-dashboard.form.input-text error-key="display_order"
                                                             name="display_order"
                                                             value="0"
                                                             id="display_order"
                                                             label-title="Display Order"/>

                                <x-dashboard.form.input-select
                                    name="parent_id"
                                    :options="$parent_destinations"
                                    track-by="id"
                                    option-lable="title"
                                    label-title="Parent Destination"
                                    id="parent_id"
                                    error-key="parent_id"/>

                                <x-dashboard.form.input-checkbox resource-name="Destination"
                                                                 :value="false"
                                                                 error-key="global"
                                                                 name="global" id="global"
                                                                 label-title="Is Global?"/>


                                <x-dashboard.form.input-checkbox resource-name="Destination" :value="true"
                                                                 error-key="enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>

                                <x-dashboard.form.input-checkbox resource-name="Destination"
                                                                 resource-desc="Set Featured"
                                                                 error-key="featured" name="featured" id="featured"
                                                                 label-title="Featured"/>

                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                <x-dashboard.form.media title="Add Banner Image"
                                                        :images="old('banner')"
                                                        name="banner"/>

                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="old('gallery')"
                                                        name="featured_image"/>

                                <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                        :images="old('gallery')"
                                                        name="gallery[]"/>
                            </div>
                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <!--Start SEO-->
                <x-dashboard.form.seo-form/>
                <!--End SEO-->

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
