@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.contact-requests.store' ) }}" method="POST" class="page-body">
        @csrf

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Create ContactRequest" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.contact-requests.index') }}">ContactRequests</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert />

                
                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="name" name="name"  id="name" label-title="Name"/>

<x-dashboard.form.input-text error-key="subject" name="subject"  id="subject" label-title="Subject"/>

<x-dashboard.form.input-text error-key="email" name="email"  id="email" label-title="Email"/>

<x-dashboard.form.input-text error-key="phone" name="phone"  id="phone" label-title="Phone"/>

<x-dashboard.form.input-text error-key="country" name="country"  id="country" label-title="Country"/>

<x-dashboard.form.input-editor error-key="message" name="message"  id="message" label-title="Message"/>


                        <x-dashboard.form.submit-button/>
                    </div>
                </div>

                
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
