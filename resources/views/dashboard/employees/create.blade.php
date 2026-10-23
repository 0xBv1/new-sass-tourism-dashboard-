@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.employees.store') }}" method="POST" enctype="multipart/form-data" class="page-body">
        @csrf

        <x-dashboard.partials.breadcrumb title="Add Team Member" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.employees.index') }}">Team Members</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="name"
                                                                 name="name" id="name" 
                                                                 label-title="Name" required="true"/>
                                </div>
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="position"
                                                                 name="position" id="position" 
                                                                 label-title="Position" required="true"/>
                                </div>
                            </div>

                            <x-dashboard.form.input-textarea error-key="bio"
                                                             name="bio" id="bio" 
                                                             label-title="Bio"/>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="email" type="email"
                                                                 name="email" id="email" 
                                                                 label-title="Email"/>
                                </div>
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="phone"
                                                                 name="phone" id="phone" 
                                                                 label-title="Phone"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Photo</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-dashboard.form.input-text error-key="order" type="number"
                                                                 name="order" id="order" 
                                                                 label-title="Display Order" value="0"/>
                                </div>
                            </div>

                            <x-dashboard.form.input-checkbox resource-name="Team Member" :value="true"
                                                             error-key="is_active"
                                                             name="is_active" id="is_active"
                                                             label-title="Active"/>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Social Links</h5>
                        </div>
                        <div class="card-body">
                            <x-dashboard.form.input-text error-key="social_facebook"
                                                         name="social_facebook" id="social_facebook" 
                                                         label-title="Facebook URL"/>

                            <x-dashboard.form.input-text error-key="social_twitter"
                                                         name="social_twitter" id="social_twitter" 
                                                         label-title="Twitter URL"/>

                            <x-dashboard.form.input-text error-key="social_linkedin"
                                                         name="social_linkedin" id="social_linkedin" 
                                                         label-title="LinkedIn URL"/>

                            <x-dashboard.form.input-text error-key="social_instagram"
                                                         name="social_instagram" id="social_instagram" 
                                                         label-title="Instagram URL"/>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <x-dashboard.form.submit-button/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

