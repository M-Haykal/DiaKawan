@extends('user.layouts.index')

@section('title', 'Detail Blog | DiaKawan')

@section('content')
    <div class="container">
        <div class="row g-5">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Hero Image -->
                <div class="position-relative rounded-3 overflow-hidden mb-4" style="height: 350px;">
                    <img src="{{ asset('storage/' . ($blog->image ?? 'default-blog.jpg')) }}" class="w-100 h-100"
                        style="object-fit: cover;" alt="{{ $blog->title }}" loading="lazy">
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-3">
                        <h1 class="display-6 fw-bold">{{ $blog->title }}</h1>
                        <p class="mb-0">{{ $blog->subtitle ?? '' }}</p>
                    </div>
                </div>

                <!-- Meta & Content -->
                <div class="text-muted mb-3">
                    <i class="far fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($blog->created_at)->translatedFormat('l, d F Y') }}
                </div>

                <article class="fs-5">
                    {!! $blog->content !!}
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 2rem;">
                    <div class="bg-light p-4 rounded shadow-sm">
                        <h5 class="text-success fw-bold mb-3">Artikel Terbaru</h5>
                        <ul class="list-unstyled">
                            @forelse($latestBlogs as $latest)
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="{{ route('user.blogs.detail', $latest->id) }}" class="text-decoration-none">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <img src="{{ asset('storage/' . ($latest->image ?? 'default-blog.jpg')) }}"
                                                    class="img-fluid rounded" style="height: 70px; object-fit: cover;"
                                                    alt="{{ $latest->title }}">
                                            </div>
                                            <div class="col-8">
                                                <h6 class="fw-bold text-dark mb-1">{{ Str::limit($latest->title, 40) }}</h6>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($latest->created_at)->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted">Belum ada artikel.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
