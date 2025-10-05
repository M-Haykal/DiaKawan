@extends('user.layouts.index')

@section('title', 'Pembayaran')

@section('content')
    <div class="container mt-5 text-center">
        <h3>Bayar Pesanan #{{ $order->id }}</h3>
        <p>Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
        <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '/order/success/{{ $order->id }}';
                },
                onPending: function(result) {
                    window.location.href = '/order/pending/{{ $order->id }}';
                },
                onError: function(result) {
                    alert("Pembayaran gagal.");
                },
                onClose: function() {
                    alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>
@endpush
