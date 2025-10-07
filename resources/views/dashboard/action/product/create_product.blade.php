@extends('dashboard.layouts.index')

@section('title', 'Manage Product')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Product</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Create Product DiaKawan</h6>
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

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                                name="stock" value="{{ old('stock') }}">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="files" class="form-label">Upload Media Produk</label>
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                Anda dapat mengupload: <strong>1 video</strong> (mp4, avi, mov) dan <strong>3
                                    gambar</strong> (jpg, jpeg, png)
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Video (Maksimal 1)</label>
                                <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                <div id="video-preview" class="mt-2"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gambar (Maksimal 3)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple
                                    accept="image/*">
                                <div id="images-preview" class="preview-container mt-2 d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                    </div>
                    <div id="nutrition-fields">
                        <label class="form-label">Data Nutrisi</label>
                        @foreach ($nutrients as $index => $nutrient)
                            <div class="nutrition-row mb-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            name="nutrisions[{{ $index }}][name]" value="{{ $nutrient }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control"
                                            name="nutrisions[{{ $index }}][quantity]" placeholder="Jumlah"
                                            value="{{ old("nutrisions.$index.quantity") }}">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" name="nutrisions[{{ $index }}][unit]">
                                            <option value="g"
                                                {{ old("nutrisions.$index.unit") == 'g' ? 'selected' : '' }}>Gram (g)
                                            </option>
                                            <option value="mg"
                                                {{ old("nutrisions.$index.unit") == 'mg' ? 'selected' : '' }}>MiliGram (mg)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('style')
    <style>
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .preview-container img,
        .preview-container video {
            border-radius: 8px;
            object-fit: cover;
        }

        .preview-item {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .preview-item button {
            position: absolute;
            top: 4px;
            right: 4px;
            border: none;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush



@push('script')
    <script>
        // Nutrition Fields Functionality
        // document.addEventListener('DOMContentLoaded', function() {
        //     let nutritionIndex = 1;

        //     // Tombol tambah nutrisi
        //     document.getElementById('add-nutrition').addEventListener('click', function() {
        //         const container = document.getElementById('nutrition-fields');

        //         const newRow = document.createElement('div');
        //         newRow.classList.add('nutrition-row', 'mb-3');
        //         newRow.innerHTML = `
    //         <div class="row g-3">
    //             <div class="col-md-4">
    //                 <input type="text" class="form-control" name="nutrisions[${nutritionIndex}][name]" placeholder="Nama Nutrisi">
    //             </div>
    //             <div class="col-md-3">
    //                 <input type="number" class="form-control" name="nutrisions[${nutritionIndex}][quantity]" placeholder="Jumlah">
    //             </div>
    //             <div class="col-md-3">
    //                 <select class="form-select" name="nutrisions[${nutritionIndex}][unit]">
    //                     <option value="g">Gram (g)</option>
    //                     <option value="mg">MiliGram (mg)</option>
    //                 </select>
    //             </div>
    //             <div class="col-md-2">
    //                 <button type="button" class="btn btn-danger remove-nutrition">Hapus</button>
    //             </div>
    //         </div>
    //     `;

        //         container.appendChild(newRow);
        //         nutritionIndex++;
        //     });

        //     // Hapus baris nutrisi
        //     document.addEventListener('click', function(e) {
        //         if (e.target.classList.contains('remove-nutrition')) {
        //             e.target.closest('.nutrition-row').remove();
        //         }
        //     });
        // });

        document.addEventListener('DOMContentLoaded', function() {
            // ==== PREVIEW VIDEO ====
            const videoInput = document.getElementById('video');
            const videoPreview = document.getElementById('video-preview');

            videoInput.addEventListener('change', function(e) {
                videoPreview.innerHTML = ''; // reset preview
                const file = e.target.files[0];
                if (file && file.type.startsWith('video/')) {
                    const url = URL.createObjectURL(file);
                    const videoEl = document.createElement('video');
                    videoEl.src = url;
                    videoEl.controls = true;
                    videoEl.className = 'rounded border';
                    videoEl.style.width = '100%';
                    videoEl.style.maxHeight = '300px';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-outline-danger mt-2';
                    removeBtn.innerHTML = '<i class="fas fa-times me-1"></i> Hapus Video';
                    removeBtn.addEventListener('click', () => {
                        videoInput.value = ''; // reset input
                        videoPreview.innerHTML = '';
                    });

                    videoPreview.appendChild(videoEl);
                    videoPreview.appendChild(removeBtn);
                }
            });

            // ==== PREVIEW GAMBAR ====
            const imagesInput = document.getElementById('images');
            const imagesPreview = document.getElementById('images-preview');

            imagesInput.addEventListener('change', function(e) {
                imagesPreview.innerHTML = ''; // reset preview
                const files = Array.from(e.target.files);

                if (files.length > 3) {
                    alert('Maksimal hanya 3 gambar yang bisa diupload.');
                    imagesInput.value = ''; // reset input
                    return;
                }

                files.forEach((file) => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'position-relative';
                        wrapper.style.width = '100px';
                        wrapper.style.height = '100px';
                        wrapper.style.marginRight = '8px';

                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.className = 'rounded border';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className =
                            'btn btn-sm btn-danger position-absolute top-0 end-0 translate-middle badge rounded-circle';
                        removeBtn.innerHTML = '<i class="fas fa-times fa-xs"></i>';

                        removeBtn.addEventListener('click', function() {
                            wrapper.remove();
                        });

                        wrapper.appendChild(img);
                        wrapper.appendChild(removeBtn);
                        imagesPreview.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endpush
