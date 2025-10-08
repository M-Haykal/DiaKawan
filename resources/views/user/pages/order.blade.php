@extends('user.layouts.index')

@section('title', 'Riwayat Pesanan | DiaKawan')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h2 class="fw-bold text-success">
                <i class="fas fa-history me-2"></i> Riwayat Pesanan Anda
            </h2>
            <a href="{{ route('user.products.index') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Pesan Lagi
            </a>
        </div>

        <!-- Empty State -->
        @if ($orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted fw-semibold">Belum ada pesanan</h4>
                <p class="text-secondary">Pesanan Anda akan muncul di sini setelah berhasil dibuat.</p>
                <a href="{{ route('user.products.index') }}" class="btn btn-outline-success mt-3">
                    <i class="fas fa-store me-1"></i> Lihat Produk
                </a>
            </div>
        @else
            <!-- Orders List -->
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pesanan #{{ $order->id }}</strong>
                                </div>
                                <small><i class="far fa-calendar-alt me-1"></i>{{ $order->created_at->format('d M Y H:i') }}</small>
                            </div>

                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                                    <div>
                                        <span
                                            class="badge rounded-pill px-3 py-2 bg-{{ $order->status === 'pending'
                                                ? 'primary'
                                                : ($order->status === 'processing'
                                                    ? 'warning text-dark'
                                                    : ($order->status === 'delivered' || $order->status === 'arrived'
                                                        ? 'success'
                                                        : 'danger')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <div class="mt-2">
                                            <strong>Total:</strong>
                                            <span class="text-success fw-bold">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3 mt-md-0 text-md-end">
                                        <p class="mb-1">
                                            <i class="fas fa-map-marker-alt text-success me-1"></i>
                                            {{ Str::limit($order->address, 40) }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-phone text-success me-1"></i> {{ $order->phone }}
                                        </p>
                                        <p class="mb-0">
                                            <span class="badge bg-light text-success border border-success">
                                                <i class="fas fa-wallet me-1"></i>{{ ucfirst($order->payment) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <!-- Produk dalam Pesanan -->
                                <div class="row g-3">
                                    @foreach ($order->orderItems as $item)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="d-flex align-items-center bg-light rounded-3 p-2 shadow-sm">
                                                @if ($item->product && $item->product->images)
                                                    @php $img = json_decode($item->product->images)[0] ?? null; @endphp
                                                    <img src="{{ asset('storage/' . $img) }}" width="60" height="60"
                                                        class="rounded me-3 border border-success"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light me-3 rounded border border-success"
                                                        style="width: 60px; height: 60px;"></div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1 fw-semibold text-dark">
                                                        {{ $item->product ? $item->product->name : 'Produk Dihapus' }}
                                                    </h6>
                                                    <small class="text-secondary">
                                                        {{ $item->quantity }} x Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if ($order->note_order)
                                    <div class="mt-3 p-3 bg-light rounded border-start border-success">
                                        <small class="text-muted">
                                            <i class="fas fa-sticky-note text-success me-1"></i> Catatan:
                                            <span class="fst-italic">{{ $order->note_order }}</span>
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection


