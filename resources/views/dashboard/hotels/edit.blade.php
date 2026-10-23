@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.hotels.update' , $hotel) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Hotel" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.hotels.index') }}">Hotels</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="hotels">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'hotels-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'hotels-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.name"
                                                                 name="{{$localKey}}[name]"
                                                                 :value="$hotel->translateOrNew($localKey)->name"
                                                                 id="{{$localKey}}-name" label-title="Name"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.description"
                                                                 name="{{$localKey}}[description]"
                                                                 :value="$hotel->translateOrNew($localKey)->description"
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
                                                             :value="$hotel->slug"
                                                             name="slug" id="slug"
                                                             label-title="Slug"/>

                                <x-dashboard.form.input-select
                                    name="amenities[]"
                                    multible
                                    :value="$hotel->amenities->pluck('id')->toArray()"
                                    :options="$amenities"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Amenities"
                                    id="amenities"
                                    error-key="amenities"/>

                                <x-dashboard.form.input-text error-key="stars" :value="$hotel->stars"
                                                             name="stars" id="stars" label-title="Stars"/>

                                <x-dashboard.form.input-text error-key="address" name="address" id="address"
                                                             :value="$hotel->address"
                                                             label-title="Address"/>

                                <x-dashboard.form.input-text error-key="map_iframe" name="map_iframe" id="map_iframe"
                                                             :value="$hotel->map_iframe"
                                                             label-title="Map Iframe"/>


                                <x-dashboard.form.input-text error-key="phone_contact"
                                                             :value="$hotel->phone_contact"
                                                             name="phone_contact" id="phone_contact"
                                                             label-title="Phone Contact"/>

                                <x-dashboard.form.input-text error-key="whatsapp_contact"
                                                             :value="$hotel->whatsapp_contact"
                                                             name="whatsapp_contact"
                                                             id="whatsapp_contact" label-title="Whatsapp Contact"/>



                                <x-dashboard.form.input-checkbox resource-name="Hotel" :value="true"
                                                                 error-key="enabled"
                                                                 :value="$hotel->enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>


                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                <x-dashboard.form.media title="Add Banner Image"
                                                        :images="old('banner', $hotel->banner)"
                                                        name="banner"/>

                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="old('featured_image', $hotel->featured_image)"
                                                        name="featured_image"/>

                                <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                        :images="old('gallery', $hotel->gallery)"
                                                        name="gallery[]"/>
                            </div>
                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


                <!--Start SEO-->
                <x-dashboard.form.seo-form :seo="$hotel->seo"/>
                <!--End SEO-->


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
