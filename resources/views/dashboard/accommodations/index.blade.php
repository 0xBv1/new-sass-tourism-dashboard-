@extends('layouts.dashboard.app')

@section('title', 'Accommodations')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <span>Accommodations</span>
            </li>
        </ol>
        <a href="{{ route('dashboard.accommodations.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i>
            <strong>Create</strong>
        </a>
    </nav>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Accommodations
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection

@push('js')
    {{ $dataTable->scripts() }}
@endpush
