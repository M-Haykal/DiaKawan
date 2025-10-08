@extends('user.layouts.index')

@section('title', 'Home | DiaKawan')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 bg-success text-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold">Nutrisi Seimbang, Nikmat No Worry</h1>
                    <p class="lead">DiaKawan menyediakan catering harian dengan bahan alami, tanpa pengawet, dan dirancang
                        oleh ahli gizi untuk menjaga kesehatan Anda dan keluarga.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                        <a href="{{ route('user.products.index') }}" class="btn btn-light btn-lg px-4 me-md-2">Lihat Menu</a>
                        <a href="{{ route('user.konsultasi.index') }}" class="btn btn-outline-light btn-lg px-4">Konsultasi
                            Gizi</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('img/home.png') }}" width="400" height="600" class="img-fluid"
                        alt="Makanan Sehat DiaKawan">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('img/about.png') }}" width="400" height="600" class="img-fluid"
                        alt="Tentang DiaKawan">
                </div>
                <div class="col-lg-6 d-flex flex-column justify-content-center">
                    <h2 class="display-4 fw-bold text-success">Tentang DiaKawan</h2>
                    <p class="lead">DiaKawan adalah sebuah ide usaha mikro yang berdiri pada tahun 2025. Yang menyediakan
                        berbagai macam makanan sehat serta aman bagi penderita diabetes. Diambil dari kata "Dia" (dari
                        Diabetes)
                        dan "Kawan" (teman), menyiratkan produk yang aman dan bersahabat bagi penderita diabetes. DiaKawan
                        juga
                        menjadi bentuk dukungan terhadap pola hidup sehat & seimbang masyarakat Indonesia.</p>
                    <a href="{{ route('user.about') }}" class="btn btn-success w-25 mt-3">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Products Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-success">Menu Terbaru</h2>
                <p class="text-muted">Pilihan sehat untuk hari ini dan besok</p>
            </div>
            <div class="row g-4">
                @forelse ($latestProducts as $latestProduct)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset('storage/' . json_decode($latestProduct->images)[0] ?? 'default.jpg') }}"
                                class="card-img-top" alt="{{ $latestProduct->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $latestProduct->name }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($latestProduct->description, 50) }}</p>
                                <div class="mt-auto">
                                    <span class="badge bg-success">{{ $latestProduct->category->name }}</span>
                                    <div class="mt-2">
                                        <strong>Rp {{ number_format($latestProduct->price, 0, ',', '.') }}</strong>
                                        <a href="{{ route('user.products.detail', $latestProduct->id) }}"
                                            class="btn btn-sm btn-success float-end">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <h1 class="text-center">Product Not Found</h1>
                @endforelse
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('user.products.index') }}" class="btn btn-outline-success">Lihat Semua Menu</a>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking" class="py-5 bg-success text-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold">Layanan Kami</h2>
                <p class="lead">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 bg-light text-dark border-0 shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Konsultasi Gizi</h5>
                            <p class="card-text">Diskusikan kebutuhan nutrisi harian Anda bersama ahli gizi kami.</p>
                            <a href="{{ route('user.konsultasi.index') }}" class="btn btn-success">Booking Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 bg-light text-dark border-0 shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Catering Harian</h5>
                            <p class="card-text">Langganan makanan sehat harian untuk 1 minggu atau lebih.</p>
                            <a href="{{ route('user.products.index') }}" class="btn btn-success">Pilih Paket</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 bg-light text-dark border-0 shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title text-success">Event Catering</h5>
                            <p class="card-text">Sediakan makanan sehat untuk acara kantor, seminar, atau keluarga.</p>
                            <a href="#contact" class="btn btn-success">Hubungi Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="display-4 fw-bold text-success">Hubungi Kami</h2>
                    <p class="lead">Ingin tahu lebih lanjut? Tim kami siap membantu!</p>
                    <div class="mt-4">
                        <p><i class="fas fa-phone me-2"></i> +62 812-3456-7890</p>
                        <p><i class="fas fa-envelope me-2"></i> info@diakawan.com</p>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Sehat No. 123, Jakarta Selatan</p>
                    </div>
                    <div class="mt-4">
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success me-2">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:info@diakawan.com" class="btn btn-outline-success">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ratio" style="height: 300px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3262.181223264989!2d112.78635407404086!3d-7.251416071231689!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f9a27ba86bd9%3A0x6fa76631d2856e3a!2sPT%20MAHAGHORA!5e1!3m2!1sid!2sid!4v1759812710196!5m2!1sid!2sid"
                            width="100%" height="100%" style="border:0; border-radius: 8px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <!-- Optional: Font Awesome for icons -->
@endpush
