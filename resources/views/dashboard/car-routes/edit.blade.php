@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.car-routes.update' , $carRoute) }}" method="POST" id="car-route-form"  class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit CarRoute" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.car-routes.index') }}">CarRoutes</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->


        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>
                <div class="card tab2-card">
                    <div class="card-body  needs-validation">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['Route', 'Prices', 'Stops']"
                            tab-id="route-prices-stops">

                            <div class="tab-pane fade active show" id="{{ 'route-prices-stops-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'route-prices-stops-0' }}-tab">
                                {{-- Rotue --}}
                                <x-dashboard.form.input-select
                                    name="pickup_location_id"
                                    :options="$locations"
                                    :value="$carRoute->pickup_location_id"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="PickUp Location"
                                    id="pickup_location_id"
                                    error-key="pickup_location_id"/>
                                <x-dashboard.form.input-select
                                    name="destination_id"
                                    :options="$locations"
                                    :value="$carRoute->destination_id"
                                    track-by="id"
                                    option-lable="name"
                                    label-title="Destination Location"
                                    id="destination_id"
                                    error-key="destination_id"/>
                            </div> {{-- End Tab --}}

                            <div class="tab-pane fade" id="{{ 'route-prices-stops-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'route-prices-stops-1' }}-tab">
                                {{-- Prices --}}

                                <a href="javascript:;" @click.prevent="addPrice"
                                   class="text-center mb-4 btn btn-outline-primary w-100">
                                    <i class="fa fa-plus"></i> Add New Car
                                </a>

                                <div v-for="(price_group,index) in prices" class="row">
                                    <input type="hidden" :name="'prices['+index+'][id]'" :value="price_group.id">

                                    <div class="form-group row">
                                        <label :for="'price-group-car-type-'+index" class="col-xl-3 col-md-4">Car Type
                                            <i v-if="index != 0" class="fa fa-trash text-danger"
                                               @click="removePrice(index)" style="cursor: pointer"></i>
                                        </label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'price-group-car-type-'+index" required
                                                   type="text" :name="'prices['+index+'][car_type]'"
                                                   :value="price_group.car_type"
                                                   placeholder="Mini Van">

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label :for="'price-group-from-'+index" class="col-xl-3 col-md-4">From</label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'price-group-from-'+index" required
                                                   type="text" :name="'prices['+index+'][from]'"
                                                   :value="price_group.from" placeholder="1">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label :for="'price-group-to-'+index" class="col-xl-3 col-md-4">To</label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'price-group-to-'+index" required
                                                   type="text" :name="'prices['+index+'][to]'" :value="price_group.to"
                                                   placeholder="2">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label :for="'price-group-one-way-price-'+index" class="col-xl-3 col-md-4">Price
                                            (Oneway)</label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'price-group-one-way-price-'+index"
                                                   required
                                                   type="text" :name="'prices['+index+'][oneway_price]'"
                                                   :value="price_group.oneway_price"
                                                   placeholder="20">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label :for="'price-group-rounded-way-price-'+index" class="col-xl-3 col-md-4">Price
                                            (Rounded)</label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'price-group-rounded-way-price-'+index"
                                                   required :value="price_group.rounded_price"
                                                   type="text" :name="'prices['+index+'][rounded_price]'"
                                                   placeholder="50">
                                        </div>
                                    </div>

                                    <hr v-if="index != (prices.length -1)">


                                </div> {{-- End Vue loop --}}

                            </div> {{-- End Tab --}}

                            <div class="tab-pane fade" id="{{ 'route-prices-stops-2' }}" role="tabpanel"
                                 aria-labelledby="{{ 'route-prices-stops-2' }}-tab">
                                {{-- Stops --}}

                                <a href="javascript:;" @click.prevent="addStop"
                                   class="text-center mb-4 btn btn-outline-primary w-100">
                                    <i class="fa fa-plus"></i> Add New Stop
                                </a>

                                <div v-for="(stop,idx) in stops" class="row">
                                    <input type="hidden" :name="'stops['+idx+'][id]'" :value="stop.id">
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-md-4" :for="'stop-location-'+idx">Location
                                            <i v-if="idx != 0" class="fa fa-trash text-danger"
                                               @click="removeStop(idx)" style="cursor: pointer"></i>
                                        </label>
                                        <div class="col-md-8 col-xl-9">

                                            <select class="custom-select select2 form-control" aria-label="Location"
                                                    :id="'stop-location-'+idx"
                                                    :name="'stops['+idx+'][stop_location_id]'">
                                                <option value="" selected disabled>--Select Location--</option>

                                                <option v-for="(location) in locations"
                                                        :selected="location.id == stop.stop_location_id"
                                                        :value="location.id">
                                                    @{{ location.name }}
                                                </option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label :for="'stop-price-'+idx" class="col-xl-3 col-md-4">Price</label>
                                        <div class="col-xl-8 col-xl-9">
                                            <input class="form-control" :id="'stop-price-'+idx" required
                                                   type="text" :name="'stops['+idx+'][price]'"
                                                   :value="stop.price" placeholder="100">
                                        </div>
                                    </div>
                                    <hr v-if="idx != (stops.length -1)">
                                </div> {{-- End Vue Loop--}}

                            </div> {{-- End Tab --}}
                        </x-dashboard.form.multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection


@push('js')
    <script src="{{ asset('assets/admin/js/vue.min.js') }}"></script>
    <script>
        new Vue({
            el: "#car-route-form",
            data() {
                return {
                    expectedCarCount: 3,
                    locations: @json($locations->toArray()),
                    prices: @json($carRoute->prices->toArray()),
                    stops: @json($carRoute->stops->toArray()),

                }
            },
            mounted() {
                console.log(JSON.stringify(this.stops))
                if (this.prices.length === 0) {
                    for (let i = 1; i <= this.expectedCarCount; i++) {
                        this.addPrice()
                    }
                }
            },
            methods: {
                removePrice(idx) {
                    this.prices.splice(idx, 1);
                },
                removeStop(idx) {
                    this.stops.splice(idx, 1);
                },
                addPrice() {
                    this.prices.push({
                        oneway_price: null,
                        rounded_price: null,
                        car_type: null,
                        from: null,
                        to: null,
                    })
                    let index = this.prices.length-1
                    let selector = 'label[for="price-group-car-type-'+index+'"]'
                    setTimeout(() =>  $(document).scrollTop($(selector).offset().top ), 100)
                },
                addStop() {
                    this.stops.push( {
                        stop_location_id: null,
                        price: null
                    })
                    let index = this.stops.length-1
                    let selector = 'label[for="stop-location-'+index+'"]'
                    setTimeout(() => {
                        $('.select2').select2()
                        $(document).scrollTop($(selector).offset().top )
                    }, 100)
                }
            }
        })
    </script>
@endpush
