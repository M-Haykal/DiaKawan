@extends('user.layouts.index')

@section('title', 'Product | DiaKawan')

@section('content')
    <!-- Page content-->
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <div class="col mb-5">
            @forelse ($products as $product)
                <div class="card h-100">
                    <!-- Sale badge-->
                    <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">
                        {{ $product->catagory->name }}
                    </div>
                    <!-- Product image-->
                    <img class="card-img-top" src="{{ asset('storage/' . json_decode($product->images)[0]) }}"
                        alt="{{ $product->name }}">
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="text-center">
                            <!-- Product name-->
                            <h5 class="fw-bolder">{{ $product->name }}</h5>
                            <!-- Product price-->
                            <span class="text-muted text-decoration-line-through">$20.00</span>
                            Rp. {{ $product->price }}
                        </div>
                    </div>
                    <!-- Product actions-->
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Order</a></div>
                    </div>
                </div>
            @empty
                <h1 class="text-center">Product Not Found</h1>
            @endforelse
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
