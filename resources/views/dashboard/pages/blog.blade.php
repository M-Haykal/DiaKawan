@extends('dashboard.layouts.index')

@section('title', 'Manage Blog')
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
            <h1 class="h3 mb-0 text-gray-800">Manage Blog</h1>
            @can('Create Blog')
                <a href="{{ route('blogs.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-add fa-sm text-white-50"></i>Create Blog</a>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Blog DiaKawan</h6>
            </div>
            <div class="card-body">
                <div class="row row-cols-3 g-4">
                    @forelse ($blogs as $blog)
                        <div class="col">
                            <a href="{{ route('blogs.show', $blog->id) }}">
                                <div class="card text-bg-dark">
                                    <img src="{{ asset('storage/' . $blog->image) }}" class="card-img" alt="...">
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
