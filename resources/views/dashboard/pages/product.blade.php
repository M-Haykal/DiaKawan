@extends('dashboard.layouts.index')

@section('title', 'Manage Product')
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
            <h1 class="h3 mb-0 text-gray-800">Manage Product</h1>
            @can('Create Products')
                <a href="{{ route('products.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-add fa-sm text-white-50"></i>Create Product</a>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Show Product DiaKawan</h6>
            </div>
            <div class="card-body">
                <div class="row row-cols-3 g-4">
                    @forelse ($products as $product)
                        <div class="col">
                            <div class="card">
                                <img src="{{ asset('storage/' . json_decode($product->images)[0]) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <span>
                                        Stock: {{ $product->stock }}
                                    </span>
                                    <div class="btn-group d-flex justify-content-center" role="group"
                                        aria-label="Basic mixed styles example">
                                        @can('Delete Product')
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini beserta semua datanya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        @endcan
                                        @can('Detail Product')
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="btn btn-success">Detail</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col">
                            <div class="card">
                                <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/041.webp" class="card-img-top"
                                    alt="Hollywood Sign on The Hill">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">
                                        This is a longer card with supporting text below as a natural lead-in to
                                        additional content. This content is a little bit longer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
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
