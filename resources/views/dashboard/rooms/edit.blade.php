@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.rooms.update' , $room) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Room" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.rooms.index') }}">Rooms</a>
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
                                                                 :value="$room->translateOrNew($localKey)->name"
                                                                 id="{{$localKey}}-name" label-title="Name"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.description"
                                                                   name="{{$localKey}}[description]"
                                                                   :value="$room->translateOrNew($localKey)->description"
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
                                                             :value="$room->slug"
                                                             name="slug" id="slug" label-title="Slug"/>

                                <x-dashboard.form.input-select
                                    name="hotel_id"
                                    :options="$hotels"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Hotel"
                                    :value="$room->hotel_id"
                                    id="hotel_id"
                                    error-key="hotel_id"/>

                                <x-dashboard.form.input-select
                                    name="amenities[]"
                                    multible
                                    :value="$room->amenities->pluck('id')->toArray()"
                                    :options="$amenities"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Amenities"
                                    id="amenities"
                                    error-key="amenities"/>

                                <x-dashboard.form.input-checkbox resource-name="Room"
                                                                 :value="$room->enabled"
                                                                 error-key="enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>

                                <x-dashboard.form.input-text error-key="bed_count" name="bed_count" id="bed_count"
                                                             :value="$room->bed_count"
                                                             label-title="Bed Count"/>

                                <x-dashboard.form.input-text error-key="room_type" name="room_type" id="room_type"
                                                             :value="$room->room_type"
                                                             label-title="Room Type"/>

                                <x-dashboard.form.input-text error-key="max_capacity" name="max_capacity" id="max_capacity"
                                                             :value="$room->max_capacity"
                                                             label-title="Max Capacity"/>

                                <x-dashboard.form.children-pricing :children="old('children', $room->children->map(function($child) { return ['age_range' => $child->age_range, 'max_count' => $child->max_count, 'price' => $child->price, 'description' => $child->description, 'enabled' => $child->enabled]; })->toArray())"/>

                                <x-dashboard.form.adults-pricing :adults="old('adults', $room->adults->map(function($adult) { return ['age_range' => $adult->age_range, 'max_count' => $adult->max_count, 'price' => $adult->price, 'description' => $adult->description, 'enabled' => $adult->enabled]; })->toArray())"/>

                                <x-dashboard.form.input-text error-key="bed_types" name="bed_types" id="bed_types"
                                                             :value="$room->bed_types"
                                                             label-title="Bed Types"/>

                                <x-dashboard.form.input-text error-key="night_price" name="night_price" id="night_price"
                                                             :value="$room->night_price"
                                                             label-title="Night Price"/>


                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                <x-dashboard.form.media title="Add Banner Image"
                                                        :images="old('banner', $room->banner)"
                                                        name="banner"/>

                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="old('featured_image', $room->featured_image)"
                                                        name="featured_image"/>

                                <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                        :images="old('gallery', $room->gallery)"
                                                        name="gallery[]"/>
                            </div>
                        </x-dashboard.form.multi-tab-card>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <!--Start SEO-->
                <x-dashboard.form.seo-form :seo="$room->seo" />
                <!--End SEO-->

            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
