@extends('layouts.app')

@section('title', 'All Customers')

@section('content')
    {{-- Page heading --}}
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h1 class="page-title">All Customers</h1>
            <p class="page-desc">Every customer record in the database, ordered by ID. Use the filters below to narrow the list.</p>
        </div>
    </div>

    {{-- Filter form: submits to /customers/filter --}}
    @include('partials.filter-form')

    @include('partials.customer-table')
@endsection
