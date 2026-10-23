@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <x-dashboard.partials.breadcrumb title="GD & WebP Test">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.media-compression.index') }}">Media Compression</a></li>
            <li class="breadcrumb-item active">GD & WebP Test</li>
        </x-dashboard.partials.breadcrumb>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>GD Extension & WebP Support Test</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">GD Extension Status</h6>
                            @if(extension_loaded('gd'))
                                <div class="alert alert-success">
                                    <strong>✓ GD Extension is loaded</strong>
                                </div>
                                
                                @php
                                    $gdInfo = gd_info();
                                @endphp
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="200">GD Version</th>
                                        <td>{{ $gdInfo['GD Version'] ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>WebP Support</th>
                                        <td>
                                            @if(isset($gdInfo['WebP Support']) && $gdInfo['WebP Support'])
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>JPEG Support</th>
                                        <td>
                                            @if(function_exists('imagecreatefromjpeg'))
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>PNG Support</th>
                                        <td>
                                            @if(function_exists('imagecreatefrompng'))
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>GIF Support</th>
                                        <td>
                                            @if(function_exists('imagecreatefromgif'))
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>FreeType Support</th>
                                        <td>
                                            @if(isset($gdInfo['FreeType Support']) && $gdInfo['FreeType Support'])
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-warning">NO</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                
                                <h6 class="mt-4 mb-3">Full GD Information</h6>
                                <pre class="bg-light p-3 rounded"><code>@php print_r($gdInfo); @endphp</code></pre>
                            @else
                                <div class="alert alert-danger">
                                    <strong>✗ GD Extension is NOT loaded</strong>
                                    <p class="mb-0 mt-2">Please install GD extension in PHP.</p>
                                </div>
                            @endif

                            <hr>

                            <h6 class="mb-3">cwebp Tool Status</h6>
                            @php
                                $output = [];
                                $returnVar = 0;
                                exec('cwebp -version 2>&1', $output, $returnVar);
                                $cwebpAvailable = $returnVar === 0;
                            @endphp
                            
                            @if($cwebpAvailable)
                                <div class="alert alert-success">
                                    <strong>✓ cwebp tool is available</strong>
                                    <p class="mb-0 mt-2">
                                        <strong>Version:</strong><br>
                                        <code>{{ implode("\n", $output) }}</code>
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <strong>✗ cwebp tool is NOT available</strong>
                                    <p class="mb-0 mt-2">
                                        <strong>Installation Instructions:</strong><br>
                                        <strong>Windows:</strong> Download from <a href="https://developers.google.com/speed/webp/download" target="_blank">Google WebP</a> and add to PATH<br>
                                        <strong>Linux:</strong> <code>sudo apt-get install webp</code> or <code>sudo yum install libwebp-tools</code><br>
                                        <strong>macOS:</strong> <code>brew install webp</code>
                                    </p>
                                </div>
                            @endif

                            <hr>

                            <h6 class="mb-3">PHP Configuration</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">PHP Version</th>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <th>PHP SAPI</th>
                                    <td>{{ php_sapi_name() }}</td>
                                </tr>
                                <tr>
                                    <th>exec() enabled</th>
                                    <td>
                                        @php
                                            $disabled = ini_get('disable_functions');
                                            $execEnabled = strpos($disabled, 'exec') === false;
                                        @endphp
                                        @if($execEnabled)
                                            <span class="badge bg-success">YES</span>
                                        @else
                                            <span class="badge bg-danger">NO</span>
                                            <small class="text-danger d-block mt-1">exec() is disabled in php.ini</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>PHP ini file</th>
                                    <td><code>{{ php_ini_loaded_file() }}</code></td>
                                </tr>
                            </table>

                            <hr>

                            <h6 class="mb-3">Overall WebP Conversion Status</h6>
                            @php
                                $gdWebP = extension_loaded('gd') && function_exists('imagewebp');
                                $canConvert = $gdWebP || $cwebpAvailable;
                            @endphp
                            
                            @if($canConvert)
                                <div class="alert alert-success">
                                    <h6>✓ WebP Conversion is Available!</h6>
                                    <p class="mb-0">
                                        @if($cwebpAvailable)
                                            Using: <strong>cwebp tool</strong> (recommended)
                                        @elseif($gdWebP)
                                            Using: <strong>GD extension</strong>
                                        @endif
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <h6>✗ WebP Conversion is NOT Available</h6>
                                    <p class="mb-0">
                                        Please install either:
                                        <ul class="mb-0">
                                            <li>cwebp tool (recommended - easier)</li>
                                            <li>GD extension with WebP support (requires PHP recompilation)</li>
                                        </ul>
                                    </p>
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('dashboard.media-compression.index') }}" class="btn btn-primary">Back to Media Compression</a>
                                <a href="{{ route('dashboard.media-compression.test') }}" class="btn btn-secondary">Refresh Test</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
