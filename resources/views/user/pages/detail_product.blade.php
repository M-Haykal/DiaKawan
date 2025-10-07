@extends('user.layouts.index')

@section('title', 'Detail Product | DiaKawan')

@section('content')
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <!-- Gambar & Video -->
            <div class="col-md-6 mb-4 mb-md-0">
                @if ($product->video)
                    <video src="{{ asset('storage/' . $product->video) }}" controls class="w-100 rounded shadow-sm mb-3"
                        style="height: 250px; object-fit: cover;">
                        Your browser does not support the video tag.
                    </video>
                @endif

                <!-- Carousel Gambar -->
                @if ($product->images)
                    @php
                        $images = json_decode($product->images);
                    @endphp
                    @if (count($images) > 0)
                        <div id="carouselProduct" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner rounded shadow-sm">
                                @foreach ($images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                            alt="Gambar {{ $index + 1 }}" style="height: 300px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if (count($images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                            style="height: 300px;">
                            <span class="text-muted">Tidak ada gambar</span>
                        </div>
                    @endif
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                        <span class="text-muted">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <!-- Informasi Produk -->
            <div class="col-md-6">
                <div class="small mb-2 text-success fw-bold">
                    {{ $product->category?->name ?? 'Tanpa Kategori' }}
                </div>
                <h1 class="display-6 fw-bold">{{ $product->name }}</h1>
                <div class="fs-4 mb-4">
                    <span class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>

                <p class="lead mb-4">{!! Str::limit($product->description, 200) !!}</p>

                <!-- Chart Nutrisi -->
                @if ($product->nutrisionProduct->isNotEmpty())
                    <div class="mt-4 mb-4">
                        <h5 class="text-success mb-3">Informasi Nutrisi (per porsi)</h5>
                        <canvas id="nutritionChart" height="120"></canvas>
                    </div>
                @endif

                <!-- Form Order & Keranjang -->
                <div class="bg-light p-4 rounded shadow-sm">
                    <h5 class="mb-3 text-success">Pesan Sekarang</h5>

                    <!-- Order Langsung -->
                    <form action="{{ route('products.order', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1"
                                min="1" required>
                        </div>
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
                            <label for="note_order" class="form-label">Catatan (Opsional)</label>
                            <textarea name="note_order" id="note_order" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Order Langsung
                        </button>
                    </form>

                    <!-- Tambah ke Keranjang -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label for="cart_quantity" class="form-label">Jumlah untuk Keranjang</label>
                            <input type="number" name="quantity" id="cart_quantity" class="form-control" value="1"
                                min="1" required>
                        </div>
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus-circle me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('nutritionChart');

            // Daftar nutrisi yang diharapkan
            const expectedNutrients = ['Protein', 'Karbohidrat', 'Lemak', 'Gula', 'Serat'];

            // Rekomendasi batas harian (dalam gram, kecuali Kalori)
            const dailyLimits = {
                'Protein': 50, // rata-rata kebutuhan
                'Karbohidrat': 300,
                'Lemak': 70,
                'Gula': 25, // WHO: maks 25g/hari
                'Serat': 25
            };

            // Ambil data dari Laravel
            const nutrientData = @json($product->nutrisionProduct);

            // Buat objek nutrisi untuk memudahkan pencarian
            const nutrientMap = {};
            nutrientData.forEach(item => {
                nutrientMap[item.nutrision] = parseFloat(item.content_quantity) || 0;
            });

            // Siapkan data sesuai urutan expectedNutrients
            const labels = expectedNutrients;
            const actualValues = labels.map(n => nutrientMap[n] || 0);
            const limitValues = labels.map(n => dailyLimits[n] || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Kandungan dalam Produk',
                            data: actualValues,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)', // hijau success
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Batas Harian (Maks)',
                            data: limitValues,
                            type: 'line',
                            fill: false,
                            borderColor: 'rgba(220, 53, 69, 1)', // merah
                            backgroundColor: 'transparent',
                            pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                            pointRadius: 4,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (gram)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    if (context.datasetIndex === 0) {
                                        const nutrient = context.label;
                                        const limit = dailyLimits[nutrient];
                                        if (limit > 0) {
                                            const percentage = ((context.parsed.y / limit) * 100)
                                                .toFixed(1);
                                            return `${percentage}% dari batas harian`;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
