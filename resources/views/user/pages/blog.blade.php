@extends('user.layouts.index')

@section('title', 'Blog | DiaKawan')

@section('content')
    <!-- Page content-->
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @forelse ($blogs as $blog)
                <div class="col">
                    <div class="card shadow-sm"> <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                            srcset="" loading="lazy">
                        <div class="card-body">
                            <h3 class="text-success fw-bold">{{ $blog->title }}</h3>
                            <p class="card-text">{!! $blog->content !!}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a class="btn btn-success" href="{{ route('user.blogs.detail', $blog->id) }}">
                                    Lead More
                                </a>
                                <small
                                    class="text-body-secondary">{{ Carbon\Carbon::parse($blog->created_at)->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <h1>Blog Not Found</h1>
            @endforelse
            <div class="d-flex justify-content-center">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection
