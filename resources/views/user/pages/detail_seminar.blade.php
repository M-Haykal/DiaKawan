@extends('user.layouts.index')

@section('title', 'Detail Seminar | DiaKawan')

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <!-- Konten Utama -->
            <div class="col-lg-8">
                <!-- Gambar Utama -->
                @if ($seminar->image)
                    <div class="mb-4 rounded overflow-hidden shadow-sm border border-success">
                        <img src="{{ asset('storage/' . $seminar->image) }}" alt="{{ $seminar->title }}"
                            class="img-fluid w-100" style="height: 350px; object-fit: cover;">
                    </div>
                @endif

                <!-- Judul & Info -->
                <h1 class="fw-bold text-success">{{ $seminar->title }}</h1>
                <p class="text-muted">{{ $seminar->subtitle }}</p>

                <div class="mb-3">
                    <span class="badge bg-{{ $seminar->location === 'online' ? 'success' : 'primary' }}">
                        {{ ucfirst($seminar->location) }}
                    </span>
                </div>

                <!-- Detail Seminar -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="fas fa-info-circle me-1"></i> Detail Seminar
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 table-striped">
                            <tbody>
                                <tr>
                                    <th class="w-25">Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($seminar->seminar_date)->translatedFormat('l, d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($seminar->seminar_time_start)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($seminar->seminar_time_end)->format('H:i') }} WIB
                                    </td>
                                </tr>
                                <tr>
                                    <th>Host</th>
                                    <td>{{ $seminar->host_name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td><code>{{ $seminar->slug }}</code></td>
                                </tr>

                                @if ($seminar->location === 'online')
                                    <tr>
                                        <th>Link Meeting</th>
                                        <td>
                                            @if ($seminar->meet_link)
                                                <a href="{{ $seminar->meet_link }}" target="_blank"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa-video me-1"></i> Gabung Sekarang
                                                </a>
                                            @else
                                                <span class="text-muted">Belum tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <th>Link Lokasi</th>
                                        <td>
                                            @if ($seminar->location_link)
                                                <a href="{{ $seminar->location_link }}" target="_blank"
                                                    class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-map-marked-alt me-1"></i> Buka di Maps
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Koordinat</th>
                                        <td>
                                            @if ($seminar->latitude && $seminar->longitude)
                                                <span class="badge bg-success">
                                                    {{ $seminar->latitude }}, {{ $seminar->longitude }}
                                                </span>
                                            @else
                                                <span class="text-muted">Belum diatur</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white fw-semibold">
                        <i class="fas fa-align-left me-1"></i> Deskripsi
                    </div>
                    <div class="card-body description-content">
                        {!! $seminar->description !!}
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                @if ($seminar->location === 'offline' && $seminar->latitude && $seminar->longitude)
                    <div class="card border-success shadow-sm">
                        <div class="card-header bg-success text-white fw-semibold">
                            <i class="fas fa-map-marker-alt me-2"></i> Lokasi Seminar
                        </div>
                        <div class="card-body p-0">
                            <div class="map-container">
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>
                @elseif ($seminar->location === 'online')
                    <div class="card border-success shadow-sm text-center p-4">
                        <i class="fas fa-video fa-3x text-success mb-3"></i>
                        <h5 class="text-success mb-2">Seminar Online</h5>
                        <p class="text-muted small">
                            Anda akan mengikuti seminar ini melalui platform video conference.
                        </p>
                        @if ($seminar->meet_link)
                            <a href="{{ $seminar->meet_link }}" target="_blank" class="btn btn-success">
                                <i class="fas fa-link me-1"></i> Buka Link
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .description-content img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 10px 0;
        }

        .card-header i {
            font-size: 0.95rem;
        }

        .table th {
            width: 35%;
            color: #198754;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($seminar->location === 'offline' && $seminar->latitude && $seminar->longitude)
                const lat = parseFloat("{{ $seminar->latitude }}");
                const lng = parseFloat("{{ $seminar->longitude }}");

                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(
                        "<b>{{ addslashes($seminar->title) }}</b><br>{{ addslashes($seminar->host_name) }}")
                    .openPopup();
            @endif
        });
    </script>
@endpush
