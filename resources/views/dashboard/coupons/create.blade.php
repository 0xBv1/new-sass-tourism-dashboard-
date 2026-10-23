@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.coupons.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Coupon" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.coupons.index') }}">Coupons</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="title" name="title" id="title" label-title="Title"/>

                        <x-dashboard.form.input-text error-key="code" name="code" id="code" label-title="Code"/>

                        <x-dashboard.form.input-checkbox resource-name="Coupon" error-key="active"
                                                         :value="true"
                                                         name="active" id="active" label-title="Active"/>

                        <x-dashboard.form.input-text error-key="value" name="value" id="value" label-title="Value"/>

                        <x-dashboard.form.input-select :options="\App\Enums\CouponType::all()"
                                                       value="{{ \App\Enums\CouponType::FIXED->value }}"
                                                       error-key="discount_type"
                                                       name="discount_type"
                                                       id="discount_type"
                                                     label-title="Discount Type"/>

                        <x-dashboard.form.input-text class="input-datepicker" error-key="start_date" name="start_date" id="start_date"
                                                     label-title="Start Date"/>

                        <x-dashboard.form.input-text class="input-datepicker" error-key="end_date" name="end_date" id="end_date"
                                                     label-title="End Date"/>

                        <x-dashboard.form.input-text error-key="limit_per_usage" name="limit_per_usage"
                                                     id="limit_per_usage" label-title="Limit Per Usage"/>

                        <x-dashboard.form.input-text error-key="limit_per_customer" name="limit_per_customer"
                                                     id="limit_per_customer" label-title="Limit Per Customer"/>


                        <x-dashboard.form.input-select
                        name="tours[]"
                        multible
                        :options="[]"
                        track-by="id"
                        option-lable="title"
                        label-title="Tours"
                        id="tours"
                        error-key="tours"/>

                        <x-dashboard.form.input-select  name="categories[]"
                                                       multible
                                                        :options="[]"
                                                       track-by="id" option-lable="title"
                                                       label-title="Categories"
                                                       id="categories" error-key="categories"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            const tours_select_box_selector = '#tours'
            $(tours_select_box_selector).select2('destroy')
            $(tours_select_box_selector).select2({
                multiple: true,
                ajax: {
                    placeholder: 'Search for a tour',
                    minimumInputLength: 1,
                    url: "{{ route('api.tours.index') }}",
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            columns: 'id,translation.title',
                            page: params.page || 1
                        };
                        if (params.term && params.term.trim() !== '') {
                            query.title = `*${params.term}*`;
                        }
                        return query;
                    },
                    processResults: function (data) {
                        let tours =  data.data.data.map(function (tour) {
                            return {
                                id: tour.id,
                                text: tour.title
                            }
                        });
                        return {
                            pagination: {
                                more: data.data.next_page_url != null
                            },
                            results: tours
                        };
                    }
                }
            });
        })


        $(document).ready(function () {
            const tour_categories_select_box_selector = '#categories'
            $(tour_categories_select_box_selector).select2('destroy')
            $(tour_categories_select_box_selector).select2({
                multiple: true,
                ajax: {
                    url: "{{ route('api.categories.index') }}",
                    dataType: 'json',
                    placeholder: 'Search for a tour category',
                    minimumInputLength: 1,
                    data: function (params) {
                        var query = {
                            columns: 'id,translation.title',
                            page: params.page || 1
                        };
                        if (params.term && params.term.trim() !== '') {
                            query.title = `*${params.term}*`;
                        }
                        return query;
                    },
                    processResults: function (data) {
                        let categories =  data.data.data.map(function (category) {
                            return {
                                id: category.id,
                                text: category.title
                            }
                        });
                        return {
                            pagination: {
                                more: data.data.next_page_url != null
                            },
                            results: categories
                        };
                    }
                }
            });
        })
    </script>
@endpush
