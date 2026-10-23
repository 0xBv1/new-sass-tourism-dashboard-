@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.roles.update' , $role) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="Edit Role" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.roles.index') }}">Roles</a>
            </li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->


        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">

                <x-dashboard.partials.message-alert />

                <div class="card  tab2-card">
                    <div class="card-body needs-validation">
                        <x-dashboard.form.multi-tab-card
                            :tabs="['role', 'permissions']"
                            tab-id="roles-permissions">

                            <div class="tab-pane fade active show"
                                 id="{{ 'roles-permissions-0' }}" role="tabpanel"
                                 aria-labelledby="{{ 'roles-permissions-0' }}-tab">
                                <x-dashboard.form.input-text error-key="name"
                                                             :value="$role->name"
                                                             name="name" id="name"
                                                             label-title="Name"/>
                            </div>

                            <div class="tab-pane fade"
                                 id="{{ 'roles-permissions-1' }}" role="tabpanel"
                                 aria-labelledby="{{ 'roles-permissions-1' }}-tab">
                                <div class="permission-block">
                                    @foreach($groupedPermissions as $permissionGroup => $permissions)
                                        <div class="attribute-blocks">
                                            <h5 class="f-w-600 mb-3">{{ \Str::headline($permissionGroup) }}</h5>
                                            @foreach($permissions as $permission)
                                                <div class="row">
                                                    <div class="col-xl-3 col-sm-4">
                                                        <label>{{ ucwords(Str::replace(['.', '-'],' ',$permission->name)) }}</label>
                                                    </div>
                                                    <div class="col-xl-9 col-sm-8">
                                                        <div class="form-group m-checkbox-inline mb-0 custom-radio-ml d-flex radio-animated">
                                                            <label class="d-block" for="{{$permission->name}}-ani1">
                                                                <input class="radio_animated"
                                                                       id="{{$permission->name}}-ani1" type="radio"
                                                                       name="permissions[{{ $permission->id }}]"
                                                                       @checked($role->permissions->contains('id', $permission->id))
                                                                       value="{{ $permission->id }}">
                                                                Allow
                                                            </label>
                                                            <label class="d-block" for="{{$permission->name}}-ani2">
                                                                <input class="radio_animated"
                                                                       id="{{$permission->name}}-ani2" type="radio"
                                                                       name="permissions[{{ $permission->id }}]"
                                                                    @checked(!$role->permissions->contains('id', $permission->id))
                                                                >
                                                                Deny
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endforeach

                                </div>
                            </div>

                        </x-dashboard.form.multi-tab-card>
                        <x-dashboard.form.submit-button/>
                    </div>
                </div>


            </div>
        </div>
        <!-- Container-fluid Ends-->

    </form>
@endsection
