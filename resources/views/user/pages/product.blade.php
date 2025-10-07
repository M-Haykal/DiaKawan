@extends('user.layouts.index')

@section('title', 'Product | DiaKawan')

@section('content')
    <div class="container">
        <!-- Filter & Search Section -->
        <div class="bg-success text-white p-4 rounded mb-5 shadow">
            <div class="row align-items-center g-3">
                <!-- Search -->
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Cari menu sehat..."
                            value="{{ request('search') }}">
                        <button class="btn btn-light ms-2" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>

                <!-- Kategori Filter -->
                <div class="col-md-6">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('user.products.index') }}"
                            class="btn {{ !request('category') ? 'btn-light text-success fw-bold' : 'btn-outline-light' }} btn-sm">
                            Semua
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('user.products.index', ['category' => $cat->slug]) }}"
                                class="btn {{ request('category') == $cat->slug ? 'btn-light text-success fw-bold' : 'btn-outline-light' }} btn-sm">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        @if ($products->count() > 0)
            <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach ($products as $product)
                    <div class="col mb-5">
                        <div class="card h-100 shadow-sm border-0">
                            @if ($product->category)
                                <div class="badge bg-success text-white position-absolute"
                                    style="top: 0.75rem; right: 0.75rem; font-weight: 600;">
                                    {{ $product->category->name }}
                                </div>
                            @endif
                            <img class="card-img-top"
                                src="{{ asset('storage/' . (json_decode($product->images)[0] ?? 'default.jpg')) }}"
                                alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4 text-center">
                                <h5 class="fw-bold text-dark">{{ $product->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>
                                <div class="mt-2">
                                    <strong class="text-success">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <div class="card-footer p-3 bg-transparent border-0">
                                <div class="text-center">
                                    <a href="{{ route('user.products.detail', $product->id) }}"
                                        class="btn btn-success w-100">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h3 class="text-muted">Tidak ada produk ditemukan.</h3>
                <a href="{{ route('user.products.index') }}" class="btn btn-success mt-3">Reset Filter</a>
            </div>
        @endif
    </div>
@endsection

@push('script')
@endpush
