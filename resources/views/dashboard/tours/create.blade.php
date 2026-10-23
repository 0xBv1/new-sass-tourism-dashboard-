@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.tours.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Tour" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.tours.index') }}">Tours</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="tours">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'tours-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'tours-'.$localKey }}-tab">
                                    <x-dashboard.form.input-text error-key="{{$localKey}}.title"
                                                                 :value="old($localKey.'.title')"
                                                                 name="{{$localKey}}[title]" id="{{$localKey}}-title"
                                                                 label-title="Title"/>


                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.overview"
                                                                   name="{{$localKey}}[overview]"
                                                                   id="{{$localKey}}-overview" label-title="Overview"/>

                                    <x-dashboard.form.input-editor error-key="{{$localKey}}.highlights"
                                                                   name="{{$localKey}}[highlights]"
                                                                   id="{{$localKey}}-highlights"
                                                                   label-title="Highlights"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.included"
                                                                 name="{{$localKey}}[included]" class="tags-input"
                                                                 id="{{$localKey}}-included" label-title="Included"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.excluded"
                                                                 name="{{$localKey}}[excluded]" class="tags-input"
                                                                 id="{{$localKey}}-excluded" label-title="Excluded"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.type"
                                                                 name="{{$localKey}}[type]" id="{{$localKey}}-type"
                                                                 label-title="Type"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.run" name="{{$localKey}}[run]"
                                                                 id="{{$localKey}}-run" label-title="Run"/>

                                    <x-dashboard.form.input-text error-key="{{$localKey}}.pickup_time"
                                                                 name="{{$localKey}}[pickup_time]"
                                                                 id="{{$localKey}}-pickup_time"
                                                                 label-title="PickupTime"/>


                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


                {{--Tour Days--}}
                <div class="card tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.language-multi-tab-card tab-id="tour-days">
                            @foreach(config('translatable.supported_locales') as $localKey => $local)
                                <div @class(['tab-pane fade', 'active show' => $localKey == config('app.locale')])
                                     id="{{ 'tour-days-'.$localKey }}" role="tabpanel"
                                     aria-labelledby="{{ 'tour-day-'.$localKey }}-tab">
                                    <h2 style="font-weight: 700; font-size: 24px; margin-bottom: 20px; color: #333;">TOUR DAYS</h2>
                                    <x-dashboard.tours.tour-days :tour="new \App\Models\Tour()" :locale="$localKey" />
                                </div>
                            @endforeach
                        </x-dashboard.form.language-multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                <div class="card tab2-card">
                    <div class="card-body needs-validation add-product-form">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['basic', 'media', 'pricing', 'groups', 'accommodation', 'without-accommodation']"
                            tab-id="basic-media-pricing">
                            <div class="tab-pane fade active show"
                                 id="{{ 'basic-media-pricing-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-0' }}-tab">


                                <x-dashboard.form.input-text error-key="slug"
                                                             name="slug" id="slug"
                                                             label-title="Slug"/>

                                <x-dashboard.form.input-text error-key="display_order"
                                                             name="display_order"
                                                             id="display_order"
                                                             label-title="Display Order"/>

                                <x-dashboard.form.input-checkbox resource-name="Tour" error-key="enabled" name="enabled"
                                                                 id="enabled"
                                                                 label-title="Enabled"/>

                                <x-dashboard.form.input-checkbox resource-name="Tour" error-key="featured"
                                                                 name="featured" id="featured"
                                                                 label-title="Featured"/>


                                <x-dashboard.form.input-text :required="true" error-key="code" name="code" id="code"
                                                             label-title="Code"/>


                                <x-dashboard.form.input-select
                                    name="duration_in_days"
                                    :options="$relations['durations']"
                                    track-by="days"
                                    option-lable="title"
                                    label-title="Duration"
                                    id="duration_in_days"
                                    error-key="duration_in_days"/>

                                <x-dashboard.form.input-select
                                    name="categories[]"
                                    multible
                                    :options="$relations['categories']"
                                    track-by="id"
                                    option-lable="title"
                                    label-title="Tour Category"
                                    id="categories"
                                    error-key="categories"/>

                                <x-dashboard.form.input-select
                                    name="options[]"
                                    multible
                                    :options="$relations['options']"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Tour Options"
                                    id="options"
                                    error-key="options"/>


                                <x-dashboard.form.input-select
                                    name="destinations[]"
                                    multible
                                    :options="$relations['destinations']"
                                    track-by="id"
                                    option-lable="title"
                                    label-title="Tour Destinations"
                                    id="destinations"
                                    error-key="destinations"/>

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'basic-media-pricing-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-1' }}-tab">
                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="old('gallery')"
                                                        name="featured_image"/>

                                <x-dashboard.form.media title="Add Gallery" :multiple="true"
                                                        :images="old('gallery')"
                                                        name="gallery[]"/>
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'basic-media-pricing-2' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-2' }}-tab">

                                <x-dashboard.form.input-text error-key="adult_price" name="adult_price" id="adult_price"
                                                             label-title="Adult Price"/>

                                <x-dashboard.form.input-text error-key="child_price" name="child_price" id="child_price"
                                                             label-title="Child Price"/>

                                <x-dashboard.form.input-text error-key="infant_price" name="infant_price" id="infant_price"
                                                             label-title="Infant Price"/>

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'basic-media-pricing-3' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-3' }}-tab">

                                <x-dashboard.tours.tour-pricing-groups />

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'basic-media-pricing-4' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-4' }}-tab">

                                <x-dashboard.tours.package-price :tour="null" :relations="$relations" />

                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'basic-media-pricing-5' }}" role="tabpanel"
                                 aria-labelledby="{{ 'basic-media-pricing-5' }}-tab">

                                <x-dashboard.tours.package-price-without-accommodation :tour="null" :relations="$relations" />

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
@push('js')
<script>
        function Check(input) {
            var parent = input.parentNode;
            var siblings = Array.from(parent.childNodes).filter(function(node) {
                return node.nodeType === 1 && node !== input;
            });
            var thirdSibling = siblings[2];
            var inputs = thirdSibling.querySelectorAll('input[type="checkbox"]');
            var isChecked = input.checked;
            inputs.forEach(function(input) {
                input.checked = isChecked;
            });
        }

        // Additional fix for tour form - ensure hidden tab fields don't block submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form.page-body');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Handle all required fields in non-active tab panes
                    const hiddenTabs = form.querySelectorAll('.tab-pane:not(.active)');
                    hiddenTabs.forEach(function(tab) {
                        const requiredFields = tab.querySelectorAll('input[required], textarea[required], select[required]');
                        requiredFields.forEach(function(field) {
                            // Always set empty fields to empty string (null equivalent) for hidden tabs
                            const currentValue = field.value || '';
                            if (!currentValue || currentValue.trim() === '') {
                                if (field.tagName.toLowerCase() === 'select') {
                                    field.value = '';
                                } else if (field.type === 'checkbox') {
                                    field.checked = false;
                                } else {
                                    // For text inputs and textareas, set to empty string
                                    field.value = '';
                                    // Also update v-model if it's a Vue component
                                    if (field.hasAttribute('v-model') || field.getAttribute('name')) {
                                        field.setAttribute('value', '');
                                    }
                                }
                            }
                            // Remove required attribute
                            field.removeAttribute('required');
                        });
                    });
                    
                    // Also handle fields that might be in Vue components or dynamically hidden
                    const allRequiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
                    allRequiredFields.forEach(function(field) {
                        // Check if field is in a hidden container
                        let parent = field.parentElement;
                        let isHidden = false;
                        
                        while (parent && parent !== form) {
                            const style = window.getComputedStyle(parent);
                            if (style.display === 'none' || 
                                style.visibility === 'hidden' || 
                                (parent.classList.contains('tab-pane') && !parent.classList.contains('active'))) {
                                isHidden = true;
                                break;
                            }
                            parent = parent.parentElement;
                        }
                        
                        if (isHidden) {
                            // If field is empty, set it to empty string
                            const currentValue = field.value || '';
                            if (!currentValue || (typeof currentValue === 'string' && currentValue.trim() === '')) {
                                if (field.tagName.toLowerCase() === 'select') {
                                    field.value = '';
                                } else if (field.type === 'checkbox') {
                                    field.checked = false;
                                } else {
                                    field.value = '';
                                    // Update v-model binding if exists
                                    if (field.hasAttribute('v-model') || field.getAttribute('name')) {
                                        field.setAttribute('value', '');
                                    }
                                }
                            }
                            field.removeAttribute('required');
                        }
                    });
                });
            }
        });

</script>

@endpush
