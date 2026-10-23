@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.hotels.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Hotel" :hideFirst="true">
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

                                <x-dashboard.form.input-text error-key="slug"
                                                             name="slug" id="slug"
                                                             label-title="Slug"/>

                                <x-dashboard.form.input-select
                                    name="amenities[]"
                                    multible
                                    :options="$amenities"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Amenities"
                                    id="amenities"
                                    error-key="amenities"/>

                                <x-dashboard.form.input-text error-key="stars" name="stars" id="stars" label-title="Stars"/>

                                <x-dashboard.form.input-text error-key="address" name="address" id="address"
                                                             label-title="Address"/>

                                <x-dashboard.form.input-text error-key="map_iframe" name="map_iframe" id="map_iframe"
                                                             label-title="Map Iframe"/>


                                <x-dashboard.form.input-text error-key="phone_contact" name="phone_contact" id="phone_contact"
                                                             label-title="Phone Contact"/>

                                <x-dashboard.form.input-text error-key="whatsapp_contact" name="whatsapp_contact"
                                                             id="whatsapp_contact" label-title="Whatsapp Contact"/>



                                <x-dashboard.form.input-checkbox resource-name="Hotel" :value="true"
                                                                 error-key="enabled"
                                                                 name="enabled" id="enabled"
                                                                 label-title="Enabled"/>

                                <!-- Room Configuration Section -->
                                <hr class="my-4">
                                <h5 class="mb-3">Room Configuration</h5>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Note:</strong> This will automatically create the specified number of rooms with the pricing structure you define below.
                                </div>
                                
                                <x-dashboard.form.input-text error-key="room_count" name="room_count" id="room_count"
                                                             label-title="Number of Rooms" type="number" min="1" max="50"/>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Adult Prices (per night)</h6>
                                        <div class="alert alert-info mb-3">
                                            <i class="fa fa-info-circle"></i>
                                            <strong>Note:</strong> Add different pricing tiers for adults. You can add multiple price points.
                                        </div>
                                        
                                        <div id="adult-prices-container">
                                            <div class="adult-price-item card mb-3" data-adult-index="0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Number of Adults</label>
                                                                <input type="number" name="adult_prices[0][count]" id="adult_count_0" 
                                                                       class="form-control" min="1" max="20" placeholder="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Price per Night</label>
                                                                <input type="number" name="adult_prices[0][price]" id="adult_price_0" 
                                                                       class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Description</label>
                                                                <input type="text" name="adult_prices[0][description]" id="adult_desc_0" 
                                                                       class="form-control" placeholder="e.g., Standard Adult">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12 text-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-adult-price" style="display: none;">
                                                                <i class="fa fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="button" class="btn btn-success btn-sm" id="add-adult-price">
                                                <i class="fa fa-plus"></i> Add Adult Price
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Child Prices (per night)</h6>
                                        <div class="alert alert-info mb-3">
                                            <i class="fa fa-info-circle"></i>
                                            <strong>Note:</strong> Add different pricing tiers for children. You can add multiple price points.
                                        </div>
                                        
                                        <div id="child-prices-container">
                                            <div class="child-price-item card mb-3" data-child-index="0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Number of Children</label>
                                                                <input type="number" name="child_prices[0][count]" id="child_count_0" 
                                                                       class="form-control" min="1" max="20" placeholder="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Price per Night</label>
                                                                <input type="number" name="child_prices[0][price]" id="child_price_0" 
                                                                       class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Description</label>
                                                                <input type="text" name="child_prices[0][description]" id="child_desc_0" 
                                                                       class="form-control" placeholder="e.g., Child (3-12 years)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12 text-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-child-price" style="display: none;">
                                                                <i class="fa fa-trash"></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="button" class="btn btn-success btn-sm" id="add-child-price">
                                                <i class="fa fa-plus"></i> Add Child Price
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Meal Types Section -->
                                <hr class="my-4">
                                <h5 class="mb-3">Meal Types</h5>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Note:</strong> Select from popular meal types and add their prices and images (1-3 images per meal type).
                                </div>

                                <div id="meal-types-container">
                                    <div class="meal-type-item card mb-3" data-meal-index="0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Meal Type</label>
                                                        <select name="meal_types[0][type]" id="meal_type_0" class="form-control" required>
                                                            <option value="">🍽️ Select Meal Type</option>
                                                            <option value="breakfast">🌅 Breakfast</option>
                                                            <option value="lunch">☀️ Lunch</option>
                                                            <option value="dinner">🌙 Dinner</option>
                                                            <option value="brunch">🥐 Brunch</option>
                                                            <option value="high_tea">🍵 High Tea</option>
                                                            <option value="snacks">🍿 Snacks</option>
                                                            <option value="beverages">🥤 Beverages</option>
                                                            <option value="desserts">🍰 Desserts</option>
                                                            <option value="buffet">🍽️ Buffet</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <x-dashboard.form.input-text error-key="meal_types.0.price" name="meal_types[0][price]" id="meal_price_0"
                                                                                 label-title="Price" type="number" step="0.01" placeholder="0.00"/>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Enabled</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="meal_types[0][enabled]" value="1" id="meal_enabled_0" checked>
                                                            <label class="form-check-label" for="meal_enabled_0">
                                                                Enable this meal type
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <x-dashboard.form.input-text error-key="meal_types.0.description" name="meal_types[0][description]" id="meal_description_0"
                                                                                 label-title="Description" placeholder="Brief description of the meal"/>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <x-dashboard.form.media title="Add Meal Images (1-3 images)" :multiple="true"
                                                                            :images="old('meal_types.0.images')"
                                                                            name="meal_types[0][images][]"/>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12 text-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-meal-type" style="display: none;">
                                                        <i class="fa fa-trash"></i> Remove Meal Type
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-success" id="add-meal-type">
                                        <i class="fa fa-plus"></i> Add Another Meal Type
                                    </button>
                                </div>

                            </div>
                            <div class="tab-pane fade"
                                 id="{{ 'featured-media-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'featured-media-1' }}-tab">
                                <x-dashboard.form.media title="Add Banner Image"
                                                        :images="old('banner')"
                                                        name="banner"/>

                                <x-dashboard.form.media title="Add Featured Image"
                                                        :images="old('featured_image')"
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Hotel form JavaScript loaded');
    
    // Add a small delay to ensure all elements are loaded
    setTimeout(function() {
        initializeFormHandlers();
    }, 100);
});

function initializeFormHandlers() {
    let mealIndex = 1; // Start from 1 since we already have index 0
    let adultPriceIndex = 1; // Start from 1 since we already have index 0
    let childPriceIndex = 1; // Start from 1 since we already have index 0
    
    // Add adult price functionality
    const addAdultPriceBtn = document.getElementById('add-adult-price');
    if (addAdultPriceBtn) {
        addAdultPriceBtn.addEventListener('click', function() {
        const container = document.getElementById('adult-prices-container');
        const newAdultPriceHtml = `
            <div class="adult-price-item card mb-3" data-adult-index="${adultPriceIndex}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Number of Adults</label>
                                <input type="number" name="adult_prices[${adultPriceIndex}][count]" id="adult_count_${adultPriceIndex}" 
                                       class="form-control" min="1" max="20" placeholder="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price per Night</label>
                                <input type="number" name="adult_prices[${adultPriceIndex}][price]" id="adult_price_${adultPriceIndex}" 
                                       class="form-control" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <input type="text" name="adult_prices[${adultPriceIndex}][description]" id="adult_desc_${adultPriceIndex}" 
                                       class="form-control" placeholder="e.g., Standard Adult">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-adult-price">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newAdultPriceHtml);
        adultPriceIndex++;
        updateAdultRemoveButtonsVisibility();
        });
    } else {
        console.error('Add adult price button not found');
    }
    
    // Add child price functionality
    const addChildPriceBtn = document.getElementById('add-child-price');
    if (addChildPriceBtn) {
        addChildPriceBtn.addEventListener('click', function() {
        const container = document.getElementById('child-prices-container');
        const newChildPriceHtml = `
            <div class="child-price-item card mb-3" data-child-index="${childPriceIndex}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Number of Children</label>
                                <input type="number" name="child_prices[${childPriceIndex}][count]" id="child_count_${childPriceIndex}" 
                                       class="form-control" min="1" max="20" placeholder="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price per Night</label>
                                <input type="number" name="child_prices[${childPriceIndex}][price]" id="child_price_${childPriceIndex}" 
                                       class="form-control" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <input type="text" name="child_prices[${childPriceIndex}][description]" id="child_desc_${childPriceIndex}" 
                                       class="form-control" placeholder="e.g., Child (3-12 years)">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-child-price">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newChildPriceHtml);
        childPriceIndex++;
        updateChildRemoveButtonsVisibility();
        });
    } else {
        console.error('Add child price button not found');
    }
    
    // Remove adult price functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-adult-price') || e.target.closest('.remove-adult-price')) {
            const adultItem = e.target.closest('.adult-price-item');
            adultItem.remove();
            updateAdultRemoveButtonsVisibility();
        }
    });
    
    // Remove child price functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-child-price') || e.target.closest('.remove-child-price')) {
            const childItem = e.target.closest('.child-price-item');
            childItem.remove();
            updateChildRemoveButtonsVisibility();
        }
    });
    
    function updateAdultRemoveButtonsVisibility() {
        const adultItems = document.querySelectorAll('.adult-price-item');
        const removeButtons = document.querySelectorAll('.remove-adult-price');
        
        if (adultItems.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'inline-block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }
    
    function updateChildRemoveButtonsVisibility() {
        const childItems = document.querySelectorAll('.child-price-item');
        const removeButtons = document.querySelectorAll('.remove-child-price');
        
        if (childItems.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'inline-block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }
    
    // Add meal type functionality
    const addMealTypeBtn = document.getElementById('add-meal-type');
    if (addMealTypeBtn) {
        console.log('Add meal type button found');
        addMealTypeBtn.addEventListener('click', function() {
        console.log('Add meal type clicked');
        const container = document.getElementById('meal-types-container');
        if (!container) {
            console.error('Meal types container not found');
            return;
        }
        const newMealHtml = `
            <div class="meal-type-item card mb-3" data-meal-index="${mealIndex}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Meal Type</label>
                                <select name="meal_types[${mealIndex}][type]" id="meal_type_${mealIndex}" class="form-control" required>
                                    <option value="">🍽️ Select Meal Type</option>
                                    <option value="breakfast">🌅 Breakfast</option>
                                    <option value="lunch">☀️ Lunch</option>
                                    <option value="dinner">🌙 Dinner</option>
                                    <option value="brunch">🥐 Brunch</option>
                                    <option value="high_tea">🍵 High Tea</option>
                                    <option value="snacks">🍿 Snacks</option>
                                    <option value="beverages">🥤 Beverages</option>
                                    <option value="desserts">🍰 Desserts</option>
                                    <option value="buffet">🍽️ Buffet</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price</label>
                                <input type="number" name="meal_types[${mealIndex}][price]" id="meal_price_${mealIndex}" 
                                       class="form-control" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Enabled</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="meal_types[${mealIndex}][enabled]" value="1" id="meal_enabled_${mealIndex}" checked>
                                    <label class="form-check-label" for="meal_enabled_${mealIndex}">
                                        Enable this meal type
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="meal_types[${mealIndex}][description]" id="meal_description_${mealIndex}" 
                                          class="form-control" placeholder="Brief description of the meal"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Add Meal Images (1-3 images)</label>
                                <input type="file" name="meal_types[${mealIndex}][images][]" class="form-control" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-danger btn-sm remove-meal-type">
                                <i class="fa fa-trash"></i> Remove Meal Type
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', newMealHtml);
        mealIndex++;
        
        // Show remove buttons for all meal types when we have more than one
        updateRemoveButtonsVisibility();
        });
    } else {
        console.error('Add meal type button not found');
    }
    
    // Remove meal type functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-meal-type') || e.target.closest('.remove-meal-type')) {
            const mealItem = e.target.closest('.meal-type-item');
            mealItem.remove();
            updateRemoveButtonsVisibility();
        }
    });
    
    function updateRemoveButtonsVisibility() {
        const mealItems = document.querySelectorAll('.meal-type-item');
        const removeButtons = document.querySelectorAll('.remove-meal-type');
        
        if (mealItems.length > 1) {
            removeButtons.forEach(btn => btn.style.display = 'inline-block');
        } else {
            removeButtons.forEach(btn => btn.style.display = 'none');
        }
    }
}
</script>
@endpush
