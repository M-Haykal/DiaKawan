@extends('user.layouts.index')
@section('title', 'Menunggu Pembayaran')
@section('content')
    <div class="container text-center py-5">
        <i class="fas fa-clock text-warning fa-4x mb-3"></i>
        <h2>Menunggu Pembayaran</h2>
        <p>Pesanan #{{ $order->id }} belum dibayar. Silakan selesaikan pembayaran Anda.</p> <a
            href="{{ route('user.cart.index') }}" class="btn btn-outline-success">Kembali ke Keranjang</a>
    </div>
@endsection
