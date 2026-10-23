@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Create Reply">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.blog-comments.index') }}">Blog Comments</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.blog-comments.edit', $blogComment) }}">Comment #{{ $blogComment->id }}</a></li>
            <li class="breadcrumb-item active">Create Reply</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-dashboard.partials.message-alert />
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Reply to Comment</h5>
                        </div>
                        <div class="card-body">
                            <!-- Parent Comment Info -->
                            <div class="mb-4">
                                <h6>Replying to:</h6>
                                <div class="alert alert-info">
                                    <strong>{{ $blogComment->name }}</strong> ({{ $blogComment->email }})<br>
                                    <small class="text-muted">{{ $blogComment->created_at->format('M d, Y H:i') }}</small><br>
                                    <p class="mb-0 mt-2">{{ $blogComment->comment }}</p>
                                </div>
                            </div>

                            <form action="{{ route('dashboard.blog-comments.store-reply', $blogComment) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Blog</label>
                                    <input type="text" class="form-control" value="{{ $blogComment->blog->title ?? 'N/A' }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Reply <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              name="comment" rows="5" required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_approved" 
                                               id="is_approved" value="1" {{ old('is_approved') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_approved">Approve immediately</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Create Reply</button>
                                    <a href="{{ route('dashboard.blog-comments.edit', $blogComment) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
