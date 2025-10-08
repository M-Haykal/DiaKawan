@extends('user.layouts.index')

@section('title', 'Tentang Kami | DiaKawan')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-success">Tentang DiaKawan</h1>
            <p class="lead text-muted">Misi kami: Menyajikan makanan sehat yang lezat dan bergizi.</p>
        </div>

        <!-- Mission & Vision -->
        <div class="row g-5 mb-5">
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded shadow-sm h-100">
                    <h3 class="text-success mb-3"><i class="fas fa-bullseye me-2"></i> Misi Kami</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Menyajikan makanan sehat
                            yang lezat, bergizi, dan aman dikonsumsi oleh semua kalangan.
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Menggunakan bahan baku
                            alami, segar, dan berkualitas tanpa bahan berbahaya.
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Bekerja sama dengan petani
                            lokal untuk bahan segar dan berkelanjutan.</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Mengedukasi masyarakat tentang pola hidup
                            sehat melalui pilihan makanan yang seimbang.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded shadow-sm h-100">
                    <h3 class="text-success mb-3"><i class="fas fa-eye me-2"></i> Visi Kami</h3>
                    <p>Makan sehat yang ideal adalah terwujudnya masyarakat yang sadar akan pentingnya makanan bergizi
                        seimbang, dengan pilihan pangan yang mudah diakses, berkualitas, serta mendukung gaya hidup sehat
                        dan produktif. makanan sehat terpercaya dan terdepan di Indonesia yang secara konsisten menjadi
                        teman setia bagi setiap individu untuk mencapai kualitas hidup terbaik dan berkelanjutan.</p>
                </div>
            </div>
        </div>

        <!-- Story -->
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('img/about.png') }}" class="img-fluid" alt="Tim DiaKawan">
            </div>
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold text-success mb-3">Kisah Kami</h2>
                <p class="lead">DiaKawan adalah sebuah ide usaha mikro yang berdiri pada tahun 2025. Yang menyediakan
                    berbagai macam makanan sehat serta aman bagi penderita diabetes. Diambil dari kata "Dia" (dari Diabetes)
                    dan "Kawan" (teman), menyiratkan produk yang aman dan bersahabat bagi penderita diabetes. DiaKawan juga
                    menjadi bentuk dukungan terhadap pola hidup sehat & seimbang masyarakat Indonesia.
                </p>
                <p>"Nilai inti kami sederhana: Kami adalah Teman. Kami tidak menjual diet yang menyiksa, tapi kami menjual
                    dukungan konsisten untuk perjalanan hidup sehat pelanggan. Kami percaya, dengan makanan yang enak dan
                    terpercaya, komitmen sehat akan lebih mudah dipertahankan."</p>
            </div>
        </div>

        <!-- Social Media -->
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-success">Temukan Kami di Sosial Media</h2>
            <p class="text-muted">Ikuti kami untuk resep sehat, tips nutrisi, dan promo terbaru!</p>
        </div>
        <div class="row g-4 mb-5 justify-content-center">
            <div class="col-md-3">
                <a href="https://www.instagram.com/diakawan.id" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="p-4">
                            <div class="bg-light rounded-circle d-inline-block" style="width: 100px; height: 100px;">
                                <i class="fab fa-instagram fa-3x text-danger mt-3"></i>
                            </div>
                            <h5 class="mt-3">Instagram</h5>
                            <p class="text-muted">@diakawan.id</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="https://www.facebook.com/diakawan.id" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="p-4">
                            <div class="bg-light rounded-circle d-inline-block" style="width: 100px; height: 100px;">
                                <i class="fab fa-facebook-f fa-3x text-primary mt-3"></i>
                            </div>
                            <h5 class="mt-3">Facebook</h5>
                            <p class="text-muted">DiaKawan</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="https://www.tiktok.com/@diakawan.id" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="p-4">
                            <div class="bg-light rounded-circle d-inline-block" style="width: 100px; height: 100px;">
                                <i class="fab fa-tiktok fa-3x text-black mt-3"></i>
                            </div>
                            <h5 class="mt-3">TikTok</h5>
                            <p class="text-muted">@diakawan.id</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-success text-white rounded-3 p-5 text-center">
            <h3 class="mb-3">Siap Mulai Hidup Lebih Sehat?</h3>
            <p class="mb-4">Pilih menu sehat hari ini dan rasakan manfaatnya untuk tubuh Anda.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('user.products.index') }}" class="btn btn-light btn-lg px-4">Lihat Menu</a>
                <a href="{{ route('user.konsultasi.index') }}" class="btn btn-outline-light btn-lg px-4">Konsultasi
                    Gizi</a>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush