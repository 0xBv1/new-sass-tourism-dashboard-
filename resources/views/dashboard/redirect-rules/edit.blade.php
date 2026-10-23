@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.redirect-rules.update' , $redirectRule) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit RedirectRule" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.redirect-rules.index') }}">Redirect Rules</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>


                <div class="card">
                    <div class="card-body">
                        <x-dashboard.form.input-text error-key="source" name="source" :value="$redirectRule->source"
                                                     id="source" label-title="Source"/>

                        <x-dashboard.form.input-text error-key="destination" name="destination"
                                                     :value="$redirectRule->destination" id="destination"
                                                     label-title="Destination"/>

                        <x-dashboard.form.input-checkbox resource-name="Redirect Rule" :value="$redirectRule->enabled"
                                                         error-key="enabled"
                                                         name="enabled" id="enabled"
                                                         label-title="Enabled"/>

                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
