@extends('user.layouts.index')
@section('title', 'Pembayaran Berhasil')
@section('content')
    <div class="container text-center py-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h2>Pembayaran Berhasil!</h2>
        <p>Pesanan #{{ $order->id }} sedang diproses.</p>
        <a href="{{ route('user.products.index') }}" class="btn btn-success">Lanjut Belanja</a>
    </div>
@endsection
