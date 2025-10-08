@extends('user.layouts.index')

@section('title', 'Detail Produk | DiaKawan')

@section('content')
    {{-- 🎬 SECTION HEADER VIDEO PRODUK --}}
    @if ($product->video)
        <section class="position-relative" style="height: 450px; overflow: hidden;">
            <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover">
                <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>

            {{-- Overlay --}}
            <div
                class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex flex-column justify-content-center align-items-center text-white text-center px-3">
                <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
                <p class="mb-0 fs-5">{{ $product->category?->name ?? 'Tanpa Kategori' }}</p>
            </div>
        </section>
    @else
        <section class="position-relative" style="height: 450px; overflow: hidden;">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-opacity-50" style="background-color: #96d331"></div>
            <div
                class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white text-center px-3">
                <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
                <p class="mb-0 fs-5">{{ $product->category?->name ?? 'Tanpa Kategori' }}</p>
            </div>
        </section>
    @endif

    <div class="container py-5">
        <div class="row gy-5 align-items-start">
            <!-- Kolom Kiri -->
            <div class="col-lg-6">
                {{-- GALERI GAMBAR PRODUK --}}
                @if ($product->images)
                    @php $images = json_decode($product->images); @endphp
                    @if (count($images) > 0)
                        <div id="carouselProduct" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded shadow-sm border">
                                @foreach ($images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                            alt="Gambar {{ $index + 1 }}" style="height: 350px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>

                            @if (count($images) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>

                                {{-- Thumbnail --}}
                                <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                                    @foreach ($images as $index => $image)
                                        <img src="{{ asset('storage/' . $image) }}" width="70" height="70"
                                            class="rounded border {{ $index === 0 ? 'border-success' : 'border-light' }}"
                                            style="object-fit: cover; cursor: pointer;"
                                            onclick="bootstrap.Carousel.getInstance(document.querySelector('#carouselProduct')).to({{ $index }})">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm"
                            style="height: 350px;">
                            <span class="text-muted">Tidak ada gambar</span>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Kolom Kanan -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="text-success fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                    <p class="text-muted mb-4">{!! $product->description !!}</p>

                    <hr>

                    {{-- FORM ORDER --}}
                    <h5 class="text-success fw-bold mb-3"><i class="fas fa-bag-shopping me-2"></i>Pesan Sekarang</h5>

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
                        <div class="mb-3">
                            <label for="payment" class="form-label">Metode Pembayaran</label>
                            <select name="payment" id="payment" class="form-select" required>
                                <option value="cash">Bayar di Tempat (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet (GoPay, OVO, dll)</option>
                                <option value="midtrans">Midtrans (Kartu/QRIS)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-2"></i> Order Langsung
                        </button>
                    </form>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3">
                            <label for="cart_quantity" class="form-label">Jumlah untuk Keranjang</label>
                            <input type="number" name="quantity" id="cart_quantity" class="form-control"
                                value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus-circle me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- INFORMASI NUTRISI --}}
        @if ($product->nutrisionProduct->isNotEmpty())
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-apple-alt me-2"></i>Informasi Nutrisi (per porsi)</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="nutritionChart"></canvas>
                            </div>
                            <p class="text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i> Data nutrisi dibandingkan dengan batas konsumsi
                                harian rata-rata.
                            </p>
                            <ul class="list-group">
                                <li class="list-group-item active bg-success" aria-current="true">Sumber Data:</li>
                                <li class="list-group-item"><a
                                        href="https://www.alodokter.com/ketahui-kebutuhan-protein-harian-yang-perlu-dipenuhi-setiap-hari">Protein</a>
                                </li>
                                <li class="list-group-item"><a
                                        href="https://hellosehat.com/nutrisi/fakta-gizi/aturan-kebutuhan-karbohidrat-sehari/">Karbohidrat</a>
                                </li>
                                <li class="list-group-item"><a
                                        href="https://www.alodokter.com/ini-anjuran-konsumsi-gula-garam-dan-lemak-per-hari">Lemak</a>
                                </li>
                                <li class="list-group-item"><a
                                        href="https://ayosehat.kemkes.go.id/penting-ini-yang-perlu-anda-ketahui-mengenai-konsumsi-gula-garam-dan-lemak">Gula</a>
                                </li>
                                <li class="list-group-item"><a
                                        href="https://kalbenutritionals.com/id/health-corner/wajib-tahu-kebutuhan-serat-tiap-orang-ternyata-beda-loh">Serat</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .chart-container {
            position: relative;
            width: 100%;
        }

        .carousel-item img {
            transition: transform 0.3s ease;
        }

        .carousel-item:hover img {
            transform: scale(1.02);
        }

        textarea,
        input {
            border-radius: 10px !important;
        }

        .btn {
            border-radius: 10px;
        }

        .card {
            border-radius: 15px;
        }

        video.object-fit-cover {
            object-fit: cover;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('nutritionChart').getContext('2d');
            const expectedNutrients = ['Protein', 'Karbohidrat', 'Lemak', 'Gula', 'Serat'];
            const dailyLimits = {
                'Protein': 57,
                'Karbohidrat': 298,
                'Lemak': 20,
                'Gula': 50,
                'Serat': 38
            };
            const nutrientData = @json($product->nutrisionProduct);
            const nutrientMap = {};
            nutrientData.forEach(item => {
                let value = parseFloat(item.content_quantity) || 0;
                if (item.unit === 'mg') value = value / 1000;
                nutrientMap[item.nutrision] = value;
            });

            const labels = expectedNutrients;
            const actualValues = labels.map(n => nutrientMap[n] || 0);
            const limitValues = labels.map(n => dailyLimits[n] || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kandungan Produk (gram)',
                        data: actualValues,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Batas Harian (gram)',
                        data: limitValues,
                        type: 'line',
                        fill: false,
                        borderColor: 'rgba(220, 53, 69, 1)',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                        pointRadius: 4,
                        borderWidth: 2
                    }]
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
                            position: 'top'
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
