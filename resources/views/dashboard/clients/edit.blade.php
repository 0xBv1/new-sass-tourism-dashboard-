@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.clients.update' , $client) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Client" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.clients.index') }}">Clients</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="name" name="name" :value="$client->name" id="name"
                                                     label-title="Name"/>

                        <x-dashboard.form.input-text error-key="email" name="email" :value="$client->email" id="email"
                                                     label-title="Email"/>

                        <x-dashboard.form.input-password error-key="password" name="password" id="password"
                                                         label-title="Password"/>

                        <x-dashboard.form.input-text error-key="phone" name="phone" :value="$client->phone" id="phone"
                                                     label-title="Phone"/>

                        <x-dashboard.form.input-text error-key="nationality" name="nationality"
                                                     :value="$client->nationality" id="nationality"
                                                     label-title="Nationality"/>

                        <x-dashboard.form.input-text class="input-datepicker" error-key="birthdate" name="birthdate"
                                                     :value="optional($client->birthdate)->format('Y-m-d')"
                                                     id="birthdate" label-title="Birthdate"/>

                        <x-dashboard.form.input-checkbox resource-desc="Block" resource-name="Client"
                                                         error-key="blocked" name="blocked" :value="$client->blocked"
                                                     id="blocked" label-title="Blocked"/>


                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
