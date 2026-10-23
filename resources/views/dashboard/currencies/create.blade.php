@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.currencies.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Currency" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.currencies.index') }}">Currencies</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid needs-validation">
            <div class="row">
                <x-dashboard.partials.message-alert/>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-checkbox error-key="active" name="active"
                                                         id="active" label-title="Active"/>

                        <x-dashboard.form.input-text error-key="name" name="name" id="name" label-title="Name"/>

                        <x-dashboard.form.input-text error-key="symbol" name="symbol" id="symbol" label-title="Symbol"/>

                        <x-dashboard.form.input-text error-key="exchange_rate" name="exchange_rate" id="exchange_rate"
                                                     label-title="Exchange Rate"/>


                        <x-dashboard.form.media name="icon"
                                                title="Add Currency Icon"  />

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
