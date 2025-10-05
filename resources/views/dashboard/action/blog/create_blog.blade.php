@extends('dashboard.layouts.index')

@section('title', 'Create Blog')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Blog</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Create Blog DiaKawan</h6>
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
                <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <!-- Tambahkan elemen preview di sini -->
                        <img id="imagePreview" src="#" alt="Preview Gambar" class="mt-3"
                            style="display: none; max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 5px; padding: 5px;" />
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" required onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" required>
                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle"
                            name="subtitle" required>
                        @error('subtitle')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror content-blog" id="content" name="content"></textarea>
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('style')
    <style>

    </style>
@endpush

@push('script')
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea.content-blog',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount', 'image'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_css: '//www.tiny.cloud/css/codepen.min.css',

            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });

                editor.on('init', function() {
                    var form = editor.getElement().form;
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            if (editor.getContent().trim() === '') {
                                e.preventDefault();
                                alert('Content is required');
                                editor.focus();
                            }
                        });
                    }
                });
            }
        });

        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const imgPreview = document.getElementById('imagePreview');
                imgPreview.src = reader.result;
                imgPreview.style.display = 'block';
            };

            // Panggil fungsi readAsDataURL jika file dipilih
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
