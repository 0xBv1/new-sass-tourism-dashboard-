@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.subscribes.update', $subscribe) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <x-dashboard.partials.breadcrumb title="Edit Subscriber" :hideFirst="true">
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
                                                     label-title="Email Address" 
                                                     :value="$subscribe->email" required="true"/>

                        <x-dashboard.form.input-text error-key="name"
                                                     name="name" id="name" 
                                                     label-title="Name" 
                                                     :value="$subscribe->name"/>

                        <x-dashboard.form.input-checkbox resource-name="Subscriber" 
                                                         :value="$subscribe->is_active"
                                                         error-key="is_active"
                                                         name="is_active" id="is_active"
                                                         label-title="Active"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

