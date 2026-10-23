@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.subscribes.store') }}" method="POST" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Add Subscriber" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.subscribes.index') }}">Subscribers</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="email" type="email"
                                                     name="email" id="email" 
                                                     label-title="Email Address" required="true"/>

                        <x-dashboard.form.input-text error-key="name"
                                                     name="name" id="name" 
                                                     label-title="Name (Optional)"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

