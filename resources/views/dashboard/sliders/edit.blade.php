@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Edit Slider" :hideFirst="true">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.sliders.index') }}">Sliders</a>
            </li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <x-dashboard.partials.message-alert/>

                <div class="col-md-8">
                    <form action="{{ route('dashboard.sliders.update', $slider) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card">
                            <div class="card-header">
                                <h5>Slider Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-dashboard.form.input-text error-key="name"
                                                                     name="name" id="name" 
                                                                     label-title="Name" 
                                                                     :value="$slider->name" required="true"/>
                                    </div>
                                    <div class="col-md-6">
                                        <x-dashboard.form.input-text error-key="key"
                                                                     name="key" id="key" 
                                                                     label-title="Key" 
                                                                     :value="$slider->key" required="true"/>
                                    </div>
                                </div>

                                <x-dashboard.form.input-textarea error-key="description"
                                                                 name="description" id="description" 
                                                                 label-title="Description"
                                                                 :value="$slider->description"/>

                                <x-dashboard.form.input-checkbox resource-name="Slider" :value="$slider->is_active"
                                                                 error-key="is_active"
                                                                 name="is_active" id="is_active"
                                                                 label-title="Active"/>

                                <x-dashboard.form.submit-button/>
                            </div>
                        </div>
                    </form>

                    <!-- Slides Section -->
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Slides</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                                <i class="fa fa-plus"></i> Add Slide
                            </button>
                        </div>
                        <div class="card-body">
                            @if($slider->slides->isEmpty())
                                <p class="text-muted">No slides added yet.</p>
                            @else
                                <div class="row">
                                    @foreach($slider->slides as $slide)
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <img src="{{ $slide->image_url }}" class="card-img-top" 
                                                     style="height: 150px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6>{{ $slide->title ?? 'Untitled' }}</h6>
                                                    <p class="small text-muted">{{ $slide->subtitle }}</p>
                                                    <form action="{{ route('dashboard.sliders.remove-slide', [$slider, $slide]) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Remove this slide?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Info</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Key</th>
                                    <td><code>{{ $slider->key }}</code></td>
                                </tr>
                                <tr>
                                    <th>Slides</th>
                                    <td>{{ $slider->slides->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($slider->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Slide Modal -->
    <div class="modal fade" id="addSlideModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('dashboard.sliders.add-slide', $slider) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Slide</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image *</label>
                                    <input type="file" name="image" class="form-control" required accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mobile Image</label>
                                    <input type="file" name="mobile_image" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Link URL</label>
                                    <input type="text" name="link" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Button Text</label>
                                    <input type="text" name="button_text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Button Style</label>
                                    <select name="button_style" class="form-select">
                                        @foreach($buttonStyles as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 mt-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

