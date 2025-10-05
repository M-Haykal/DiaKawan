@extends('dashboard.layouts.index')

@section('title', 'Manage Seminar')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manage Seminar</h1>
            @can('Create Seminar')
                <a href="{{ route('seminars.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-add fa-sm text-white-50"></i>Create Seminar</a>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Show Seminar DiaKawan</h6>
            </div>
            <div class="card-body">
                <div class="row row-cols-3 g-4">
                    @forelse ($seminars as $seminar)
                        <div class="card p-0">
                            <img src="{{ asset('storage/' . $seminar->image) }}" class="card-img-top" alt="{{ $seminar->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $seminar->title }}</h5>
                                <p class="card-text">{!! $seminar->description !!}</p>
                                <p class="card-text"><small class="text-body-secondary">{{ $seminar->seminar_date }}</small></p>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection

@push('style')
    <style>

    </style>
@endpush



@push('script')
    <script></script>
@endpush
