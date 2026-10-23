@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.newsletters.store') }}" method="POST" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Create Newsletter" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.newsletters.index') }}">Newsletters</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <x-dashboard.form.input-text error-key="subject"
                                                         name="subject" id="subject" 
                                                         label-title="Subject" required="true"/>

                            <x-dashboard.form.input-editor error-key="body"
                                                           name="body" id="body" 
                                                           label-title="Email Body" required="true"/>

                            <x-dashboard.form.submit-button text="Save as Draft"/>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>{{ $subscribersCount }}</strong> active subscribers will receive this newsletter.
                            </div>
                            <p class="text-muted">
                                The newsletter will be saved as a draft first. You can review and send it later.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

