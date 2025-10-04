@extends('dashboard.layouts.index')

@section('title', 'Detail Product')
@section('content')

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Product</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Produk {{ $product->name }}</h6>
                @can('Edit Product')
                    <div>
                        <input type="checkbox" id="editToggle" class="form-check-input me-2">
                        <label for="editToggle" class="form-check-label">Edit Mode</label>
                    </div>
                @endcan
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

                <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $product->name) }}" disabled>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" disabled>{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Harga & Stok --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="{{ old('price', $product->price) }}" disabled>
                        </div>
                        @can('Edit Product')
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock"
                                    value="{{ old('stock', $product->stock) }}" disabled>
                            </div>
                        @endcan
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" disabled>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Media --}}
                    <div class="mb-3">
                        <label class="form-label">Media Produk</label>
                        <div class="row">
                            {{-- Video --}}
                            <div class="col-md-6 mb-3">
                                <label for="video" class="form-label">Video</label>

                                <div id="existing-video" class="mb-2">
                                    @if ($product->video)
                                        <video controls class="w-100 rounded border mb-2">
                                            <source src="{{ asset('storage/' . $product->video) }}">
                                            Browser tidak mendukung video.
                                        </video>
                                    @else
                                        <p class="text-muted">Tidak ada video</p>
                                    @endif
                                </div>

                                <div id="video-preview" class="mt-2"></div>

                                {{-- input untuk mengganti video (disabled di mode detail) --}}
                                <input type="file" class="form-control" id="video" name="video" accept="video/*"
                                    disabled>
                                <small class="text-muted">Pilih file untuk mengganti video (opsional)</small>
                            </div>

                            {{-- Gambar --}}
                            <div class="col-md-6 mb-3">
                                <label for="images" class="form-label">Gambar</label>

                                <div id="existing-images" class="d-flex flex-wrap gap-2 mb-2">
                                    @if ($product->images && is_array(json_decode($product->images, true)))
                                        @foreach (json_decode($product->images, true) as $img)
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ asset('storage/' . $img) }}" width="100" height="100"
                                                    class="rounded border" style="object-fit:cover;">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-image"
                                                    data-path="{{ $img }}" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Tidak ada gambar</p>
                                    @endif
                                </div>

                                <input type="hidden" name="deleted_images" id="deleted_images">
                                <div id="images-preview" class="preview-container mb-2"></div>

                                <input type="file" class="form-control" id="images" name="images[]" multiple
                                    accept="image/*" disabled>
                                <small class="text-muted">Pilih gambar untuk mengganti (maks 3). Jika tidak dipilih, gambar
                                    lama tetap.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Nutrisi --}}
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label class="form-label mb-0">Data Nutrisi</label>
                        <button type="button" id="add-nutrition" class="btn btn-secondary btn-sm" disabled>Tambah
                            Nutrisi</button>
                    </div>

                    <div id="nutrition-fields">
                        @foreach ($product->nutrisionProduct as $index => $nutr)
                            <div class="nutrition-row mb-3">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            name="nutrisions[{{ $index }}][name]" value="{{ $nutr->nutrision }}"
                                            disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control"
                                            name="nutrisions[{{ $index }}][quantity]"
                                            value="{{ $nutr->content_quantity }}" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" name="nutrisions[{{ $index }}][unit]"
                                            disabled>
                                            <option value="g" {{ $nutr->unit == 'g' ? 'selected' : '' }}>Gram (g)
                                            </option>
                                            <option value="mg" {{ $nutr->unit == 'mg' ? 'selected' : '' }}>MiliGram
                                                (mg)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary d-none" id="saveButton">Simpan Perubahan</button>
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

        .position-relative {
            position: relative;
            display: inline-block;
        }

        .delete-image {
            position: absolute;
            top: 4px;
            right: 4px;
            z-index: 10;
            /* penting agar tombol muncul di atas gambar */
            opacity: 0.9;
            cursor: pointer;
            pointer-events: auto;
            /* memastikan tombol bisa diklik */
        }

        .delete-image:disabled {
            opacity: 0.5;
            pointer-events: none;
            /* kalau masih disabled di mode non-edit */
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const editToggle = document.getElementById('editToggle');
            const saveButton = document.getElementById('saveButton');
            const addNutritionBtn = document.getElementById('add-nutrition');
            const productForm = document.getElementById('productForm');
            const inputs = productForm ? productForm.querySelectorAll('input, textarea, select') : [];
            const videoInput = document.getElementById('video');
            const imagesInput = document.getElementById('images');
            const videoPreview = document.getElementById('video-preview');
            const imagesPreview = document.getElementById('images-preview');
            const existingImages = document.getElementById('existing-images');
            const existingVideo = document.getElementById('existing-video');
            const nutritionContainer = document.getElementById('nutrition-fields');

            // Set nutritionIndex based on existing nutrition rows
            let nutritionIndex = nutritionContainer ? nutritionContainer.querySelectorAll('.nutrition-row').length :
                0;

            // Toggle edit mode
            if (editToggle) {
                editToggle.addEventListener('change', function() {
                    const editable = editToggle.checked;
                    inputs.forEach(input => {
                        if (input.name !== '_token' && input.name !== '_method') {
                            input.disabled = !editable;
                        }
                    });
                    if (addNutritionBtn) addNutritionBtn.disabled = !editable;
                    saveButton.classList.toggle('d-none', !editable);

                    // Tambahan ini
                    document.querySelectorAll('.delete-image').forEach(btn => {
                        btn.disabled = !editable;
                    });
                });

            }

            let deletedImages = [];

            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-image')) {
                    const btn = e.target.closest('.delete-image');
                    const imagePath = btn.dataset.path;
                    if (confirm('Hapus gambar ini?')) {
                        deletedImages.push(imagePath);
                        btn.parentElement.remove();
                        document.getElementById('deleted_images').value = JSON.stringify(deletedImages);
                    }
                }
            });

            // Add nutrition row (only if button exists)
            if (addNutritionBtn) {
                addNutritionBtn.addEventListener('click', function() {
                    const container = nutritionContainer;
                    if (!container) return;

                    const newRow = document.createElement('div');
                    newRow.classList.add('nutrition-row', 'mb-3');
                    newRow.innerHTML = `
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="nutrisions[${nutritionIndex}][name]" placeholder="Nama Nutrisi">
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" name="nutrisions[${nutritionIndex}][quantity]" placeholder="Jumlah">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="nutrisions[${nutritionIndex}][unit]">
                                    <option value="g">Gram (g)</option>
                                    <option value="mg">MiliGram (mg)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-nutrition">Hapus</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(newRow);
                    nutritionIndex++;
                });
            }

            // Delegated remove nutrition
            document.addEventListener('click', function(e) {
                if (e.target.classList && e.target.classList.contains('remove-nutrition')) {
                    const row = e.target.closest('.nutrition-row');
                    if (row) row.remove();
                }
            });

            // Video preview (when user picks replacement)
            if (videoInput) {
                videoInput.addEventListener('change', function(e) {
                    videoPreview.innerHTML = '';
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('video/')) {
                        // hide existing preview while showing new preview
                        if (existingVideo) existingVideo.style.display = 'none';

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
                        removeBtn.innerHTML = '<i class="fas fa-times me-1"></i> Hapus Preview';
                        removeBtn.addEventListener('click', () => {
                            videoInput.value = '';
                            videoPreview.innerHTML = '';
                            if (existingVideo) existingVideo.style.display = '';
                        });

                        videoPreview.appendChild(videoEl);
                        videoPreview.appendChild(removeBtn);
                    } else {
                        if (existingVideo) existingVideo.style.display = '';
                    }
                });
            }

            // Images preview (when user picks replacement)
            if (imagesInput) {
                imagesInput.addEventListener('change', function(e) {
                    imagesPreview.innerHTML = '';
                    const files = Array.from(e.target.files);

                    if (files.length > 3) {
                        alert('Maksimal hanya 3 gambar yang bisa diupload.');
                        imagesInput.value = '';
                        if (existingImages) existingImages.style.display = '';
                        return;
                    }

                    if (existingImages) existingImages.style.display = 'none';

                    files.forEach((file) => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'position-relative preview-item';

                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.style.width = '100%';
                            img.style.height = '100%';
                            img.style.objectFit = 'cover';
                            img.className = 'rounded border';

                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className =
                                'btn btn-sm btn-danger position-absolute top-0 end-0 translate-middle badge rounded-circle';
                            removeBtn.innerHTML = '<i class="fas fa-times fa-xs"></i>';

                            removeBtn.addEventListener('click', function() {
                                wrapper.remove();
                                // note: cannot remove file from input easily; user can re-select files again if needed
                            });

                            wrapper.appendChild(img);
                            wrapper.appendChild(removeBtn);
                            imagesPreview.appendChild(wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
@endpush
