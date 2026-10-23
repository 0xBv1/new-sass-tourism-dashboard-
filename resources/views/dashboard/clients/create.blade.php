@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.clients.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create Client" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.clients.index') }}">Clients</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid needs-validation">
            <div class="row">
                <x-dashboard.partials.message-alert/>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="name" name="name" id="name" label-title="Name"/>

                        <x-dashboard.form.input-text error-key="email" name="email" id="email" label-title="Email"/>

                        <x-dashboard.form.input-password required error-key="password" name="password" id="password"
                                                     label-title="Password"/>

                        <x-dashboard.form.input-text error-key="phone" name="phone" id="phone" label-title="Phone"/>

                        <x-dashboard.form.input-text error-key="nationality" name="nationality" id="nationality"
                                                     label-title="Nationality"/>

                        <x-dashboard.form.input-text class="input-datepicker allow-past"
                                                     data-allow-past="truex"
                                                     error-key="birthdate" name="birthdate" id="birthdate"
                                                     label-title="Birthdate"/>

                        <x-dashboard.form.input-checkbox resource-desc="Block"
                                                         resource-name="Client" error-key="blocked" name="blocked" id="blocked"
                                                         label-title="Blocked"/>


                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
