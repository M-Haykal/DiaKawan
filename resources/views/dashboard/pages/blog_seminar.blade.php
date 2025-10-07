@extends('dashboard.layouts.index')

@section('title', 'Manage Blog and Seminar')
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
            <h1 class="h3 mb-0 text-gray-800">Manage Blog and Seminar</h1>
            <div class="d-flex justify-content-between">
                @can('Create Blog')
                    <a href="{{ route('blogs.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mx-2"><i
                            class="fas fa-add fa-sm text-white-50"></i>Create Blog</a>
                @endcan
                @can('Create Seminar')
                    <a href="{{ route('seminars.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mx-2"><i
                            class="fas fa-add fa-sm text-white-50"></i>Create Seminar</a>
                @endcan
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Blog and SeminarDiaKawan</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-fill nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="fill-tab-0" data-bs-toggle="tab" href="#blog" role="tab"
                            aria-controls="blog" aria-selected="true"> Blog </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="fill-tab-1" data-bs-toggle="tab" href="#seminar" role="tab"
                            aria-controls="seminar" aria-selected="false"> Seminar </a>
                    </li>
                </ul>
                <div class="tab-content pt-5" id="tab-content">
                    <div class="tab-pane active" id="blog" role="tabpanel" aria-labelledby="fill-tab-0">
                        <div class="row row-cols-3 g-4">
                            @forelse ($blogs as $blog)
                                <div class="col">
                                    <a href="{{ route('blogs.show', $blog->id) }}">
                                        <div class="card text-bg-dark">
                                            <img src="{{ asset('storage/' . $blog->image) }}" class="card-img"
                                                alt="...">
                                            <div class="card-img-overlay">
                                                <h5 class="card-title">{{ $blog->title }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <p class="text-center">No Blog Found</p>
                            @endforelse

                            <div class="d-flex justify-content-center">
                                {{ $blogs->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="seminar" role="tabpanel" aria-labelledby="fill-tab-1">
                        <div class="row row-cols-3 g-4">
                            @forelse ($seminars as $seminar)
                                <div class="col">
                                    <a href="{{ route('seminars.show', $seminar->id) }}">
                                        <div class="card text-bg-dark">
                                            <img src="{{ asset('storage/' . $seminar->image) }}" class="card-img"
                                                alt="...">
                                            <div class="card-img-overlay">
                                                <h5 class="card-title">{{ $seminar->title }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <p class="text-center">No Seminar Found</p>
                            @endforelse

                            <div class="d-flex justify-content-center">
                                {{ $seminars->links() }}
                            </div>
                        </div>
                    </div>
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
