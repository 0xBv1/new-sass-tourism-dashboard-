@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="Media Compression">
            <li class="breadcrumb-item active">Media Compression</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <!-- Statistics Card -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Media Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="mb-0 text-primary">{{ number_format($stats['total_files']) }}</h3>
                                        <p class="mb-0 text-muted">Total Files</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="mb-0 text-info">{{ $stats['total_size_formatted'] }}</h3>
                                        <p class="mb-0 text-muted">Total Size</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="mb-0 text-success">{{ number_format($stats['compressible']) }}</h3>
                                        <p class="mb-0 text-muted">Compressible Images</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h3 class="mb-0 text-warning">{{ number_format($stats['webp_count']) }}</h3>
                                        <p class="mb-0 text-muted">WebP Files</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h6>Files by Type:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary">Images: {{ $stats['images'] }}</span>
                                        <span class="badge bg-danger">Videos: {{ $stats['videos'] }}</span>
                                        <span class="badge bg-info">Documents: {{ $stats['documents'] }}</span>
                                        <span class="badge bg-secondary">Other: {{ $stats['other'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('dashboard.media-compression.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Directory</label>
                                    <select name="directory" class="form-select">
                                        @foreach($availableDirectories ?? ['assets'] as $dir)
                                            <option value="{{ $dir }}" {{ $directory === $dir ? 'selected' : '' }}>
                                                {{ ucfirst($dir) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Search files...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Compression Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Compress Images</h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="selectAllCompressible()">Select All Compressible</button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">Deselect All</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(!$webpSupported['webp_available'])
                                <div class="alert alert-{{ $webpSupported['cwebp_available'] ? 'info' : 'danger' }}">
                                    <h6>WebP Support Status</h6>
                                    @if($webpSupported['cwebp_available'])
                                        <p class="mb-0">
                                            <strong>✓ cwebp tool is available!</strong> WebP conversion will use the cwebp command-line tool.
                                        </p>
                                    @else
                                        <p class="mb-0">
                                            @if(!$webpSupported['gd_loaded'])
                                                <strong>GD extension is not installed.</strong> Please install the GD extension in PHP.
                                            @elseif(!function_exists('imagewebp'))
                                                <strong>WebP support is not available in GD.</strong> 
                                            @endif
                                        </p>
                                        <p class="mt-2 mb-0">
                                            <strong>Solution:</strong> Install Google's WebP tools (cwebp) for better WebP conversion support.
                                        </p>
                                        <p class="mt-2 mb-0">
                                            <small>
                                                <strong>Windows:</strong> Download from <a href="https://developers.google.com/speed/webp/download" target="_blank">Google WebP</a> and add to PATH<br>
                                                <strong>Linux:</strong> <code>sudo apt-get install webp</code> or <code>sudo yum install libwebp-tools</code><br>
                                                <strong>macOS:</strong> <code>brew install webp</code>
                                            </small>
                                        </p>
                                        <p class="mt-2 mb-0">
                                            <small>Current GD Version: {{ $webpSupported['gd_version'] ?? 'Not available' }}</small>
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="mt-3">
                                <a href="{{ route('dashboard.media-compression.test') }}" class="btn btn-sm btn-info">
                                    <i data-feather="info"></i> Test GD & WebP Support
                                </a>
                            </div>
                            <form id="compressForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Quality (1-100)</label>
                                        <input type="number" id="quality" name="quality" class="form-control" value="85" min="1" max="100">
                                        <small class="text-muted">Higher quality = larger file size. Recommended: 80-90</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="deleteOriginal" name="delete_original" value="1">
                                            <label class="form-check-label" for="deleteOriginal">
                                                Delete original files after compression
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success" id="compressBtn" {{ !$webpSupported['webp_available'] ? 'disabled' : '' }}>
                                    <i data-feather="zap"></i> Compress Selected Images
                                </button>
                                @if(!$webpSupported['webp_available'])
                                    <p class="text-danger mt-2 mb-0">
                                        <small>Compression is disabled because WebP support is not available.</small>
                                    </p>
                                @elseif($webpSupported['cwebp_available'])
                                    <p class="text-info mt-2 mb-0">
                                        <small>Using cwebp tool for conversion (recommended).</small>
                                    </p>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Media Files List -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Media Files</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                                            </th>
                                            <th>Preview</th>
                                            <th>Name</th>
                                            <th>Extension</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Modified</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mediaFiles as $file)
                                            <tr>
                                                <td>
                                                    @if($file['is_compressible'])
                                                        <input type="checkbox" name="files[]" class="file-checkbox" value="{{ $file['path'] }}" data-size="{{ $file['size'] }}">
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($file['is_image'])
                                                        <img src="{{ asset($file['path']) }}" alt="{{ $file['name'] }}" style="max-width: 50px; max-height: 50px; object-fit: cover;" class="img-thumbnail">
                                                    @else
                                                        <i data-feather="file" class="text-muted"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $file['name'] }}</small><br>
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($file['path'], 50) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ strtoupper($file['extension']) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $file['type'] === 'image' ? 'primary' : ($file['type'] === 'video' ? 'danger' : ($file['type'] === 'document' ? 'info' : 'secondary')) }}">
                                                        {{ ucfirst($file['type']) }}
                                                    </span>
                                                </td>
                                                <td>{{ $file['size_formatted'] }}</td>
                                                <td><small>{{ $file['modified'] }}</small></td>
                                                <td>
                                                    @if($file['is_webp'])
                                                        <span class="badge bg-success">WebP</span>
                                                    @elseif($file['is_compressible'])
                                                        <span class="badge bg-warning">Can Compress</span>
                                                    @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No media files found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Modal -->
    <div class="modal fade" id="resultsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compression Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="resultsContent">
                    <!-- Results will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    let selectedFiles = [];

    function toggleAll(checkbox) {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
            if (checkbox.checked) {
                selectedFiles.push(cb.value);
            } else {
                selectedFiles = [];
            }
        });
    }

    function selectAllCompressible() {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = true;
            if (!selectedFiles.includes(cb.value)) {
                selectedFiles.push(cb.value);
            }
        });
        document.getElementById('selectAll').checked = true;
    }

    function deselectAll() {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        selectedFiles = [];
        document.getElementById('selectAll').checked = false;
    }

    // Update selected files array when checkboxes change
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    if (!selectedFiles.includes(this.value)) {
                        selectedFiles.push(this.value);
                    }
                } else {
                    selectedFiles = selectedFiles.filter(f => f !== this.value);
                }
            });
        });
    });

    // Handle form submission
    document.getElementById('compressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        @if(!$webpSupported['gd_loaded'] || !$webpSupported['webp_available'])
            alert('WebP support is not available. Please install GD extension with WebP support.');
            return;
        @endif
        
        const selectedCheckboxes = document.querySelectorAll('.file-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one file to compress.');
            return;
        }

        const files = Array.from(selectedCheckboxes).map(cb => cb.value);
        const quality = document.getElementById('quality').value;
        const deleteOriginal = document.getElementById('deleteOriginal').checked;

        // Confirm if deleting original
        let confirmMessage = `Are you sure you want to compress ${files.length} file(s)?`;
        if (deleteOriginal) {
            confirmMessage += '\n\nWARNING: Original files will be deleted after compression!';
        }
        
        if (!confirm(confirmMessage)) {
            return;
        }

        // Disable button and show loading
        const btn = document.getElementById('compressBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Compressing...';

        // Make AJAX request
        fetch('{{ route('dashboard.media-compression.compress') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                files: files,
                quality: parseInt(quality),
                delete_original: deleteOriginal
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i data-feather="zap"></i> Compress Selected Images';
            
            if (data.success) {
                showResults(data.results);
                // Reload page after 3 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = '<i data-feather="zap"></i> Compress Selected Images';
            alert('Error: ' + error.message);
        });
    });

    function showResults(results) {
        let html = '<div class="table-responsive">';
        html += '<table class="table table-sm">';
        html += '<thead><tr><th>File</th><th>Original Size</th><th>Compressed Size</th><th>Saved</th><th>Status</th></tr></thead>';
        html += '<tbody>';

        // Success
        results.success.forEach(item => {
            html += `<tr class="table-success">
                <td><small>${item.file}</small></td>
                <td>${item.original_size}</td>
                <td>${item.compressed_size}</td>
                <td><strong class="text-success">${item.saved} (${item.saved_percentage}%)</strong></td>
                <td><span class="badge bg-success">Success</span></td>
            </tr>`;
        });

        // Failed
        results.failed.forEach(item => {
            html += `<tr class="table-danger">
                <td><small>${item.file}</small></td>
                <td colspan="3">${item.error}</td>
                <td><span class="badge bg-danger">Failed</span></td>
            </tr>`;
        });

        // Skipped
        results.skipped.forEach(item => {
            html += `<tr class="table-warning">
                <td><small>${item.file}</small></td>
                <td colspan="3">${item.reason}</td>
                <td><span class="badge bg-warning">Skipped</span></td>
            </tr>`;
        });

        html += '</tbody></table></div>';

        // Summary
        const totalSaved = formatBytes(results.total_original_size - results.total_compressed_size);
        html += `<div class="alert alert-info mt-3">
            <h6>Summary:</h6>
            <p class="mb-0">
                <strong>Success:</strong> ${results.success.length} | 
                <strong>Failed:</strong> ${results.failed.length} | 
                <strong>Skipped:</strong> ${results.skipped.length}<br>
                <strong>Total Saved:</strong> ${totalSaved}
            </p>
        </div>`;

        document.getElementById('resultsContent').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('resultsModal'));
        modal.show();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
</script>
@endpush
