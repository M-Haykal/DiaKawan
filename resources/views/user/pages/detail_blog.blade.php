@extends('user.layouts.index')

@section('title', 'Detail Blog | DiaKawan')

@section('content')
    <main class="container">
        <!-- Jumbotron -->
        <div class="p-5 text-center bg-image rounded-3"
            style="
                background-image: url('{{ asset('storage/' . $blog->image) }}');
                height: 400px;
                background-size: cover;">
            <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
            </div>
        </div>
        <!-- Jumbotron -->
        <div class="row g-5">
            <div class="col-md-8">
                <article class="blog-post">
                    <h1 class="display-5 mb-1">{{ $blog->title }}</h1>
                    <p>{{ $blog->subtitle }}</p>
                    <small class="blog-post-meta">Created at {{ $blog->created_at }}</small>
                    <p class="border-top">{!! $blog->content !!}</p>
            </div>
            <div class="col-md-4">
                <div class="position-sticky my-2">
                    <div>
                        <h4 class="fst-italic">Recent posts</h4>
                        <ul class="list-unstyled">
                            @forelse ($latestBlogs as $latestBlog)
                                <li> <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top"
                                        href="#"> <img src="{{ asset('storage/' . $latestBlog->image) }}"
                                            alt="{{ $latestBlog->title }}" class="bd-placeholder-img " height="96"
                                            width="100%" loading="lazy">
                                        <div class="col-lg-8">
                                            <h6 class="mb-0">{{ $latestBlog->title }}</h6>
                                            <small class="text-body-secondary">{{ $latestBlog->created_at }}</small>
                                        </div>
                                    </a>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
