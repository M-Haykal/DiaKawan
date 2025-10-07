@extends('user.layouts.index')

@section('title', 'Booking Konsultasi | DiaKawan')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h2 class="mb-0">Booking Konsultasi Gizi</h2>
                        <p class="mb-0">Isi form berikut untuk menjadwalkan sesi konsultasi dengan ahli gizi kami</p>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('user.konsultasi.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">No. WhatsApp</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="Contoh: 081234567890" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="booking_date" class="form-label">Tanggal Konsultasi</label>
                                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror"
                                        id="booking_date" name="booking_date" value="{{ old('booking_date') }}"
                                        min="{{ now()->format('Y-m-d') }}" required>
                                    @error('booking_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="booking_time" class="form-label">Waktu Konsultasi</label>
                                    <input type="time" class="form-control @error('booking_time') is-invalid @enderror"
                                        id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
                                    @error('booking_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Lokasi Konsultasi</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="location" id="location_online"
                                        value="online" {{ old('location', 'online') == 'online' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="location_online">Online (via Zoom/Google
                                        Meet)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="location" id="location_offline"
                                        value="offline" {{ old('location') == 'offline' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="location_offline">Offline (Kantor DiaKawan,
                                        Jakarta)</label>
                                </div>
                                @error('location')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control" id="note" name="note" rows="3"
                                    placeholder="Contoh: Saya memiliki alergi kacang...">{{ old('note') }}</textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Kirim Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
