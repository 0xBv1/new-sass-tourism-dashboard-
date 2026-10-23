@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.newsletters.update', $newsletter) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <x-dashboard.partials.breadcrumb title="Edit Newsletter" :hideFirst="true">
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
                                                         label-title="Subject" 
                                                         :value="$newsletter->subject" required="true"/>

                            <x-dashboard.form.input-editor error-key="body"
                                                           name="body" id="body" 
                                                           label-title="Email Body" 
                                                           :value="$newsletter->body" required="true"/>

                            <x-dashboard.form.submit-button text="Update Draft"/>
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
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Send Newsletter</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.newsletters.send', $newsletter) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Send this newsletter to all subscribers?')">
                                    <i class="fa fa-paper-plane me-2"></i> Send Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

