@extends('user.layouts.index')

@section('title', 'Blog | DiaKawan')

@section('content')
    <div class="container">
        <!-- Search Section -->
        <div class="bg-success text-white p-4 rounded mb-5 shadow">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="mb-3 text-center">Artikel Kesehatan & Gizi</h1>
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari artikel (misal: diet sehat, gula darah, dll)..."
                            value="{{ request('search') }}">
                        <button class="btn btn-light ms-2" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Blog Grid -->
        @if ($blogs->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                @foreach ($blogs as $blog)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . ($blog->image ?? 'default-blog.jpg')) }}" class="card-img-top"
                                alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-success fw-bold">{{ $blog->title }}</h5>
                                <p class="card-text flex-grow-1">
                                    {!! Str::limit(strip_tags($blog->content), 120) !!}
                                </p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <a href="{{ route('user.blogs.detail', $blog->id) }}"
                                        class="btn btn-outline-success btn-sm">
                                        Baca Selengkapnya
                                    </a>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $blogs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted">Tidak ada artikel ditemukan.</h4>
                @if (request('search'))
                    <a href="{{ route('user.blogs.index') }}" class="btn btn-success mt-3">Lihat Semua Blog</a>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('script')
@endpush
