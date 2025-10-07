@extends('user.layouts.index')

@section('title', 'Seminar | DiaKawan')

@section('content')
    <div class="container">
        <!-- Header & Search -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bolder text-success">Seminar Terbaru</h1>
            <p class="lead text-muted">Ikuti seminar kesehatan & nutrisi terkini dari ahli gizi profesional</p>

            <!-- Search Form -->
            <div class="col-md-8 mx-auto mt-4">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari seminar (misal: diabetes, pola makan, dll)..." value="{{ request('search') }}">
                    <button class="btn btn-success ms-2" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Seminar -->
        @if ($seminars->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada seminar yang tersedia.</h4>
                @if (request('search'))
                    <a href="{{ route('user.seminars.index') }}" class="btn btn-success mt-3">
                        <i class="fas fa-redo me-1"></i> Tampilkan Semua
                    </a>
                @endif
            </div>
        @else
            <div class="row g-4">
                @foreach ($seminars as $seminar)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            @if ($seminar->image)
                                <img src="{{ asset('storage/' . $seminar->image) }}" class="card-img-top"
                                    alt="{{ $seminar->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-success opacity-50"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        class="badge bg-{{ $seminar->location == 'online' ? 'success' : 'primary' }} text-white">
                                        {{ ucfirst($seminar->location) }}
                                    </span>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($seminar->seminar_date)->format('d M Y') }}
                                    </small>
                                </div>

                                <h5 class="card-title fw-bold text-dark mb-2">
                                    {{ Str::limit($seminar->title, 45) }}
                                </h5>

                                <p class="card-text text-muted small mb-2">
                                    <i class="far fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($seminar->seminar_time_start)->format('H:i') }} –
                                    {{ \Carbon\Carbon::parse($seminar->seminar_time_end)->format('H:i') }} WIB
                                </p>

                                <p class="card-text flex-grow-1 text-muted">
                                    {{ Str::limit($seminar->subtitle, 70) }}
                                </p>

                                <a href="{{ route('user.seminars.detail', $seminar->id) }}"
                                    class="btn btn-success mt-3 w-100">
                                    <i class="fas fa-info-circle me-1"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $seminars->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('script')
@endpush
