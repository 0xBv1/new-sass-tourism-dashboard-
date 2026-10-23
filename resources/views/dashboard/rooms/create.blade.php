@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.rooms.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Room" :hideFirst="true">
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
                                                                 name="{{$localKey}}[name]" id="{{$localKey}}-name"
                                                                 label-title="Name"/>

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

                                <x-dashboard.form.input-text error-key="slug" name="slug" id="slug" label-title="Slug"/>

                                <x-dashboard.form.input-select
                                    name="hotel_id"
                                    :options="$hotels"
                                    :value="old('hotel_id', request('hotel_id'))"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Hotel"
                                    id="hotel_id"
                                    error-key="hotel_id"/>

                                <x-dashboard.form.input-select
                                    name="amenities[]"
                                    multible
                                    :options="$amenities"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Amenities"
                                    id="amenities"
                                    error-key="amenities"/>

                                <x-dashboard.form.input-checkbox resource-name="Room"
                                                                 :value="true"
                                                                 error-key="enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>

                                <div class="row">
                                    <div class="col-md-3">
                                        <x-dashboard.form.input-text error-key="bed_count" name="bed_count" id="bed_count"
                                                                     label-title="Bed Count" type="number" min="1" max="10" placeholder="2"/>
                                    </div>
                                    <div class="col-md-3">
                                        <x-dashboard.form.input-text error-key="bed_types" name="bed_types" id="bed_types"
                                                                     label-title="Bed Types" placeholder="e.g., Double, Queen, King"/>
                                    </div>
                                    <div class="col-md-3">
                                        <x-dashboard.form.input-text error-key="room_type" name="room_type" id="room_type"
                                                                     label-title="Room Type" placeholder="e.g., Standard, Deluxe, Suite"/>
                                    </div>
                                    <div class="col-md-3">
                                        <x-dashboard.form.input-text error-key="max_capacity" name="max_capacity" id="max_capacity"
                                                                     label-title="Max Capacity" type="number" min="1" max="20" placeholder="4"/>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <x-dashboard.form.input-text error-key="night_price" name="night_price" id="night_price"
                                                                     label-title="Night Price" type="number" step="0.01" placeholder="0.00"/>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Room Features</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="has_balcony" value="1" id="has_balcony">
                                                <label class="form-check-label" for="has_balcony">
                                                    Has Balcony
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="has_sea_view" value="1" id="has_sea_view">
                                                <label class="form-check-label" for="has_sea_view">
                                                    Sea View
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="has_air_conditioning" value="1" id="has_air_conditioning" checked>
                                                <label class="form-check-label" for="has_air_conditioning">
                                                    Air Conditioning
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <x-dashboard.form.adults-pricing :adults="old('adults', [])"/>
                                <x-dashboard.form.children-pricing :children="old('children', [])"/>


                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                
                                <div class="alert alert-info mb-4">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Room Images:</strong> Add high-quality images to showcase your room. Featured image will be the main display image.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-dashboard.form.media title="Featured Image (Main Display)"
                                                                :images="old('featured_image')"
                                                                name="featured_image"/>
                                    </div>
                                    <div class="col-md-6">
                                        <x-dashboard.form.media title="Banner Image (Header)"
                                                                :images="old('banner')"
                                                                name="banner"/>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <x-dashboard.form.media title="Room Gallery (Multiple Images)"
                                                            :multiple="true"
                                                            :images="old('gallery')"
                                                            name="gallery[]"/>
                                    <small class="form-text text-muted">
                                        <i class="fa fa-lightbulb"></i>
                                        <strong>Tip:</strong> Upload 3-5 high-quality images showing different angles of the room, bathroom, and amenities.
                                    </small>
                                </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add tooltips to help users understand the fields
    const tooltips = [
        { selector: '#bed_count', text: 'Number of beds in the room (e.g., 2 for double room)' },
        { selector: '#bed_types', text: 'Type of beds (e.g., Double, Queen, King, Single)' },
        { selector: '#room_type', text: 'Category of room (e.g., Standard, Deluxe, Suite, Executive)' },
        { selector: '#max_capacity', text: 'Maximum number of people the room can accommodate' },
        { selector: '#night_price', text: 'Base price per night for this room' }
    ];
    
    tooltips.forEach(function(tooltip) {
        const element = document.querySelector(tooltip.selector);
        if (element) {
            element.setAttribute('title', tooltip.text);
            element.setAttribute('data-toggle', 'tooltip');
            element.setAttribute('data-placement', 'top');
        }
    });
    
    // Initialize tooltips if Bootstrap is available
    if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    
    // Add validation feedback for bed count
    const bedCountInput = document.getElementById('bed_count');
    if (bedCountInput) {
        bedCountInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 1) {
                this.setCustomValidity('Bed count must be at least 1');
            } else if (value > 10) {
                this.setCustomValidity('Bed count cannot exceed 10');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Add validation feedback for max capacity
    const maxCapacityInput = document.getElementById('max_capacity');
    if (maxCapacityInput) {
        maxCapacityInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 1) {
                this.setCustomValidity('Max capacity must be at least 1');
            } else if (value > 20) {
                this.setCustomValidity('Max capacity cannot exceed 20');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
@endpush
