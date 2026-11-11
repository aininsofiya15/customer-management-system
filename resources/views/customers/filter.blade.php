@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="page-desc">{{ $description }}</p>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-accent">
            <i class="bi bi-arrow-left me-1"></i> Back to All Customers
        </a>
    </div>

    {{-- Filter form: keeps the selected values --}}
    @include('partials.filter-form')

    @if ($hasFilter)
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="text-muted-sm">Active filters:</span>
            @foreach ($chips as $chip)
                <a href="{{ $chip['url'] }}" class="filter-chip" title="{{ $chip['tooltip'] }}">
                    {{ $chip['label'] }} <i class="bi bi-x-lg"></i>
                </a>
            @endforeach
            <a href="{{ route('customers.index') }}" class="text-muted-sm ms-1">Clear all</a>
        </div>
    @endif

    @include('partials.customer-table')
@endsection
