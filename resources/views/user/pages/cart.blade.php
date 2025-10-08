@extends('user.layouts.index')

@section('title', 'Keranjang | DiaKawan')

@section('content')
    <div class="container py-5">
        <h2 class="text-success mb-4">Keranjang Belanja Anda</h2>

        @if ($items->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Keranjang Anda kosong</h4>
                <a href="{{ route('user.products.index') }}" class="btn btn-success mt-3">
                    <i class="fas fa-utensils me-1"></i> Lihat Menu
                </a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product->images)
                                                        @php $img = json_decode($item->product->images)[0] ?? null; @endphp
                                                        <img src="{{ asset('storage/' . $img) }}" width="60"
                                                            class="rounded me-3" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light me-3"
                                                            style="width: 60px; height: 60px; border-radius: 8px;"></div>
                                                    @endif
                                                    <span>{{ $item->product->name }}</span>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        min="1" class="form-control form-control-sm d-inline"
                                                        style="width: 70px;" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Hapus dari keranjang?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-success shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Checkout</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total:</span>
                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                            <hr>

                            <!-- Form Checkout dari Keranjang -->
                            <form action="{{ route('user.cart.checkout') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Pengiriman</label>
                                    <textarea name="address" id="address" class="form-control" rows="2" required>{{ Auth::user()->address ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="{{ Auth::user()->phone ?? '' }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="payment" class="form-label">Metode Pembayaran</label>
                                    <select name="payment" id="payment" class="form-select" required>
                                        <option value="cash">Bayar di Tempat (Cash)</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="ewallet">E-Wallet (GoPay, OVO, dll)</option>
                                        <option value="midtrans">Midtrans (Kartu/QRIS)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="note_order" class="form-label">Catatan (Opsional)</label>
                                    <textarea name="note_order" id="note_order" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle me-2"></i> Bayar Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
