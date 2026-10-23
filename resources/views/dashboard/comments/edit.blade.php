@extends('layouts.dashboard.app')

@section('content')
    <form action="{{ route('dashboard.comments.update', $comment) }}" method="POST" class="page-body">
        @csrf
        @method('PUT')

        <x-dashboard.partials.breadcrumb title="Edit Comment" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.comments.index') }}">Comments</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Comment Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $comment->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $comment->email }}</td>
                                </tr>
                                <tr>
                                    <th>Tour</th>
                                    <td>{{ $comment->tour?->title }}</td>
                                </tr>
                                <tr>
                                    <th>Rating</th>
                                    <td>{{ $comment->stars }}</td>
                                </tr>
                                <tr>
                                    <th>Comment</th>
                                    <td>{{ $comment->comment }}</td>
                                </tr>
                                <tr>
                                    <th>Submitted</th>
                                    <td>{{ $comment->created_at->format('F j, Y g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Moderation</h5>
                        </div>
                        <div class="card-body">
                            <x-dashboard.form.input-checkbox resource-name="Comment" :value="$comment->is_approved"
                                                             error-key="is_approved"
                                                             name="is_approved" id="is_approved"
                                                             label-title="Approved"/>

                            <x-dashboard.form.input-checkbox resource-name="Comment" :value="$comment->is_featured"
                                                             error-key="is_featured"
                                                             name="is_featured" id="is_featured"
                                                             label-title="Featured"/>

                            <x-dashboard.form.submit-button/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

