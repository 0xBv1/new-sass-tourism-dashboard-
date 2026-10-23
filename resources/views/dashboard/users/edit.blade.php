@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.users.update' , $user) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit User" :hideFirst="true" >
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.users.index') }}">Users</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->


        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="name" name="name" :value="$user->name" id="name"
                                                     label-title="Name"/>

                        <x-dashboard.form.input-text error-key="email" name="email" :value="$user->email" id="email"
                                                     label-title="Email"/>

                        <x-dashboard.form.input-password error-key="password" name="password"
                                                     id="password" label-title="Password"/>

                        <x-dashboard.form.input-text error-key="position" name="position" :value="$user->position"
                                                     id="position" label-title="Position"/>

                        <x-dashboard.form.input-select error-key="phone_code"
                                                       :options="$countries_phone_codes"
                                                       option-lable="name"
                                                       track-by="id"
                                                       name="phone_code" :value="$user->phone_code"
                                                       id="phone_code"
                                                       label-title="Phone Code"/>

                        <x-dashboard.form.input-text error-key="phone" name="phone" :value="$user->phone" id="phone"
                                                     label-title="Phone"/>

                        <x-dashboard.form.input-select error-key="roles"
                                                       :options="$roles"
                                                       option-lable="name"
                                                       track-by="id"
                                                       :value="$user->roles->pluck('id')->toArray()"
                                                       :multible="true"
                                                       name="roles[]" id="roles"
                                                       label-title="Roles"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
