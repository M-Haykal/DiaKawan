@extends('dashboard.layouts.index')

@section('title', 'Detail Seminar')
@section('content')

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Seminar</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Seminar: {{ $seminar->title }}</h6>
                <div class="d-flex align-items-center">
                    <label class="me-3">
                        <input type="checkbox" id="editModeCheckbox"> Edit Mode
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

                <form action="{{ route('seminars.update', $seminar->id) }}" method="POST" enctype="multipart/form-data"
                    id="seminarForm">
                    @csrf
                    @method('PUT')

                    {{-- IMAGE PREVIEW --}}
                    <div class="form-group">
                        <img id="imagePreview" src="{{ $seminar->image ? asset('storage/' . $seminar->image) : '#' }}"
                            alt="Preview Gambar" class="mt-3"
                            style="display: {{ $seminar->image ? 'block' : 'none' }}; max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" />
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" disabled
                            onchange="previewImage(event)">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar</small>
                    </div>

                    {{-- TITLE --}}
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $seminar->title) }}" disabled required>
                    </div>

                    {{-- SUBTITLE --}}
                    <div class="form-group">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                            value="{{ old('subtitle', $seminar->subtitle) }}" disabled required>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control description" id="description" name="description" disabled>{{ old('description', $seminar->description) }}</textarea>
                    </div>

                    {{-- HOST --}}
                    <div class="form-group">
                        <label for="host_name" class="form-label">Host Name</label>
                        <input type="text" class="form-control" id="host_name" name="host_name"
                            value="{{ old('host_name', $seminar->host_name) }}" disabled required>
                    </div>

                    {{-- DATE & TIME --}}
                    <div class="form-group">
                        <label for="seminar_date" class="form-label">Seminar Date</label>
                        <input type="date" class="form-control" id="seminar_date" name="seminar_date"
                            value="{{ old('seminar_date', (string) $seminar->seminar_date) }}" disabled required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="seminar_time_start" class="form-label">Seminar Time Start</label>
                            <input type="time" class="form-control" id="seminar_time_start" name="seminar_time_start"
                                value="{{ old('seminar_time_start', $seminar->seminar_time_start ? \Carbon\Carbon::parse($seminar->seminar_time_start)->format('H:i') : '') }}"
                                disabled required>
                        </div>
                        <div class="col-md-6">
                            <label for="seminar_time_end" class="form-label">Seminar Time End</label>
                            <input type="time" class="form-control" id="seminar_time_end" name="seminar_time_end"
                                value="{{ old('seminar_time_end', $seminar->seminar_time_end ? \Carbon\Carbon::parse($seminar->seminar_time_end)->format('H:i') : '') }}"
                                disabled required>
                        </div>
                    </div>

                    {{-- LOCATION TYPE --}}
                    <div class="form-group mt-3">
                        <label class="me-3">
                            <input type="radio" name="location" value="online"
                                {{ $seminar->location == 'online' ? 'checked' : '' }} disabled> Online
                        </label>
                        <label>
                            <input type="radio" name="location" value="offline"
                                {{ $seminar->location == 'offline' ? 'checked' : '' }} disabled> Offline
                        </label>
                    </div>

                    {{-- LINK ONLINE / LOKASI --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="meet_link" class="form-label">Meet Link</label>
                            <input type="text" class="form-control" id="meet_link" name="meet_link"
                                value="{{ old('meet_link', $seminar->meet_link) }}" disabled required>
                        </div>
                        <div class="col-md-6 location-section">
                            <label for="location_link" class="form-label">Location Link</label>
                            <input type="text" class="form-control" id="location_link" name="location_link"
                                value="{{ old('location_link', $seminar->location_link) }}" disabled>
                        </div>
                    </div>

                    {{-- MAP SECTION --}}
                    <div class="map-section mt-3" style="display: none;">
                        <div class="map-container">
                            <div id="map"></div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude"
                                    value="{{ old('latitude', $seminar->latitude) }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude"
                                    value="{{ old('longitude', $seminar->longitude) }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success" disabled>Simpan Perubahan</button>
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
        let map, marker;
        let isEditMode = false;

        tinymce.init({
            selector: 'textarea.description',
            height: 300,
            menubar: false,
            plugins: ['advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
            readonly: true
        });

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

        function initMap(lat, lng) {
            const mapContainer = document.getElementById('map');
            if (!mapContainer) return;

            if (map) map.remove();

            map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lng], {
                draggable: isEditMode
            }).addTo(map);

            if (isEditMode) {
                marker.on('dragend', function() {
                    const pos = marker.getLatLng();
                    document.getElementById('latitude').value = pos.lat;
                    document.getElementById('longitude').value = pos.lng;
                });

                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    marker.on('dragend', function() {
                        const p = marker.getLatLng();
                        document.getElementById('latitude').value = p.lat;
                        document.getElementById('longitude').value = p.lng;
                    });
                });
            }
        }

        function updateLocationDisplay() {
            // Ambil nilai location dari radio yang diceklis
            const checkedRadio = document.querySelector('input[name="location"]:checked');
            const location = checkedRadio ? checkedRadio.value : '{{ $seminar->location }}';

            const locationSection = document.querySelector('.location-section');
            const mapSection = document.querySelector('.map-section');

            if (location === 'online') {
                locationSection.style.display = 'none';
                mapSection.style.display = 'none';
            } else {
                locationSection.style.display = 'block';
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;

                if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
                    mapSection.style.display = 'block';
                    initMap(parseFloat(lat), parseFloat(lng));
                } else {
                    mapSection.style.display = 'none';
                    if (map) map.remove();
                    map = null;
                }
            }
        }

        function toggleEditMode(enable) {
            isEditMode = enable;

            // Aktifkan/nonaktifkan SEMUA input kecuali checkbox editMode
            const form = document.getElementById('seminarForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.id !== 'editModeCheckbox') {
                    input.disabled = !enable;
                }
            });

            // Khusus radio location: pastikan tidak disabled saat edit
            document.querySelectorAll('input[name="location"]').forEach(radio => {
                radio.disabled = !enable;
            });

            // TinyMCE
            const editor = tinymce.get('description');
            if (editor) {
                editor.setMode(enable ? 'design' : 'readonly');
            }

            // Submit button
            document.querySelector('button[type="submit"]').disabled = !enable;

            // Update tampilan lokasi
            setTimeout(() => {
                updateLocationDisplay();
            }, 100);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editCheckbox = document.getElementById('editModeCheckbox');
            const locationRadios = document.querySelectorAll('input[name="location"]');

            editCheckbox.addEventListener('change', function() {
                toggleEditMode(this.checked);
            });

            // Tambahkan event listener ke radio
            locationRadios.forEach(radio => {
                radio.addEventListener('change', updateLocationDisplay);
            });

            // Inisialisasi awal
            updateLocationDisplay();
        });
    </script>
@endpush
