@extends('dashboard.layouts.index')

@section('title', 'Create Seminar')
@section('content')

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Seminar</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Create Seminar DiaKawan</h6>
                <div>
                    <label class="me-3">
                        <input type="radio" name="location" id="onlineRadio" value="online"> Online
                    </label>
                    <label>
                        <input type="radio" name="location" id="offlineRadio" value="offline" checked> Offline
                    </label>
                </div>
            </div>

            <div class="card-body">
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

                <form action="{{ route('seminars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- IMAGE PREVIEW --}}
                    <div class="form-group">
                        <img id="imagePreview" src="#" alt="Preview Gambar" class="mt-3"
                            style="display: none; max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" />
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" required onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TITLE --}}
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SUBTITLE --}}
                    <div class="form-group">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle"
                            name="subtitle" required>
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror description" id="description"
                            name="description"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- HOST --}}
                    <div class="form-group">
                        <label for="host_name" class="form-label">Host Name</label>
                        <input type="text" class="form-control @error('host_name') is-invalid @enderror" id="host_name"
                            name="host_name" required>
                        @error('host_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DATE & TIME --}}
                    <div class="form-group">
                        <label for="seminar_date" class="form-label">Seminar Date</label>
                        <input type="date" class="form-control @error('seminar_date') is-invalid @enderror"
                            id="seminar_date" name="seminar_date" required>
                        @error('seminar_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="seminar_time_start" class="form-label">Seminar Time Start</label>
                            <input type="time" class="form-control" id="seminar_time_start" name="seminar_time_start"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="seminar_time_end" class="form-label">Seminar Time End</label>
                            <input type="time" class="form-control" id="seminar_time_end" name="seminar_time_end"
                                required>
                        </div>
                    </div>

                    {{-- LINK ONLINE / LOKASI --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="meet_link" class="form-label">Meet Link</label>
                            <input type="text" class="form-control" id="meet_link" name="meet_link" required>
                        </div>
                        <div class="col-md-6 location-section">
                            <label for="location_link" class="form-label">Location Link</label>
                            <input type="text" class="form-control" id="location_link" name="location_link">
                        </div>
                    </div>

                    <div class="map-section mt-3">
                        <div class="map-container">
                            <div id="map"></div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude">
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="location" id="hiddenLocation" value="offline">
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-success" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .map-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            #map {
                height: 300px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        // TinyMCE
        tinymce.init({
            selector: 'textarea.description',
            height: 300,
            menubar: false,
            plugins: ['advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount', 'image'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // Preview Gambar
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();
            reader.onload = function() {
                const imgPreview = document.getElementById('imagePreview');
                imgPreview.src = reader.result;
                imgPreview.style.display = 'block';
            };
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }

        // MAP LANGSUNG TAMPIL & GUNAKAN LOKASI PENGGUNA
        let map, marker;

        function initMap() {
            const mapContainer = document.getElementById('map');

            if (!map) {
                map = L.map(mapContainer).setView([-6.200000, 106.816666], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lng]).addTo(map);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                });
            }

            // ✅ Minta lokasi pengguna
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 13);
                        if (marker) map.removeLayer(marker);
                        marker = L.marker([lat, lng]).addTo(map)
                            .bindPopup("Your current location").openPopup();
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                    },
                    function(error) {
                        console.warn("Gagal mendapatkan lokasi, gunakan default Jakarta");
                    }
                );
            } else {
                console.warn("Browser tidak mendukung geolocation");
            }
            setTimeout(() => {
                map.invalidateSize();
            }, 800);
        }

        document.addEventListener('DOMContentLoaded', initMap);

        // Radio logic (Online / Offline)
        const onlineRadio = document.getElementById('onlineRadio');
        const offlineRadio = document.getElementById('offlineRadio');
        const locationSection = document.querySelector('.location-section');
        const mapSection = document.querySelector('.map-section');

        function updateSeminarMode() {
            const hiddenInput = document.getElementById('hiddenLocation');
            if (onlineRadio.checked) {
                hiddenInput.value = 'online';
                locationSection.style.display = 'none';
                mapSection.style.display = 'none';
                document.getElementById('meet_link').required = true;
                document.getElementById('location_link').required = false;
            } else if (offlineRadio.checked) {
                hiddenInput.value = 'offline';
                locationSection.style.display = 'block';
                mapSection.style.display = 'block';
                document.getElementById('meet_link').required = false;
                document.getElementById('location_link').required = true;
            }
        }

        onlineRadio.addEventListener('change', updateSeminarMode);
        offlineRadio.addEventListener('change', updateSeminarMode);
        updateSeminarMode(); // initial load
    </script>
@endpush
