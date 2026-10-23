@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Edit Blog Comment">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.blog-comments.index') }}">Blog Comments</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Blog Comment</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.blog-comments.update', $blogComment) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Blog</label>
                                    <input type="text" class="form-control" value="{{ $blogComment->blog->title ?? 'N/A' }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $blogComment->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $blogComment->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Comment <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              name="comment" rows="5" required>{{ old('comment', $blogComment->comment) }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_approved" 
                                               id="is_approved" value="1" {{ old('is_approved', $blogComment->is_approved) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_approved">Approved</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured" 
                                               id="is_featured" value="1" {{ old('is_featured', $blogComment->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                    </div>
                                </div>

                                @if($blogComment->parent)
                                <div class="mb-3">
                                    <label class="form-label">Parent Comment</label>
                                    <div class="alert alert-info">
                                        <strong>{{ $blogComment->parent->name }}</strong> ({{ $blogComment->parent->email }})<br>
                                        <small class="text-muted">{{ $blogComment->parent->created_at->format('M d, Y H:i') }}</small><br>
                                        {{ Str::limit($blogComment->parent->comment, 200) }}
                                        <div class="mt-2">
                                            <a href="{{ route('dashboard.blog-comments.edit', $blogComment->parent) }}" class="btn btn-sm btn-outline-primary">View Parent</a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update Comment</button>
                                    <a href="{{ route('dashboard.blog-comments.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>

                            <!-- Replies Section -->
                            @if($blogComment->replies->count() > 0)
                            <div class="mt-5">
                                <h5>Replies ({{ $blogComment->replies->count() }})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($blogComment->replies as $reply)
                                            <tr>
                                                <td>{{ $reply->name }}</td>
                                                <td>{{ $reply->email }}</td>
                                                <td>{{ Str::limit($reply->comment, 100) }}</td>
                                                <td>
                                                    @if($reply->is_approved)
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                    @if($reply->is_featured)
                                                        <span class="badge bg-info">Featured</span>
                                                    @endif
                                                </td>
                                                <td>{{ $reply->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.blog-comments.edit', $reply) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @if(!$reply->is_approved)
                                                        <form action="{{ route('dashboard.blog-comments.approve', $reply) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="mt-5">
                                <p class="text-muted">No replies yet.</p>
                            </div>
                            @endif

                            <!-- Create Reply Button -->
                            <div class="mt-3">
                                <a href="{{ route('dashboard.blog-comments.create-reply', $blogComment) }}" class="btn btn-success">
                                    <i class="fa fa-reply"></i> Add Reply
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
