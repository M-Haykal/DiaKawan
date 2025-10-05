@extends('dashboard.layouts.index')

@section('title', 'Detail Blog')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Blog</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Blog {{ $blog->title }}</h6>
                @can('Edit Blog')
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

                <form id="blogForm" action="{{ route('blogs.update', $blog->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Gambar --}}
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label><br>
                        @if ($blog->image)
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="img-fluid mb-2"
                                style="max-height:200px; object-fit:cover;">
                        @endif
                        <input type="file" name="image" id="image" class="form-control" disabled>
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $blog->title) }}" disabled>
                    </div>

                    {{-- Subtitle --}}
                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control"
                            value="{{ old('subtitle', $blog->subtitle) }}" disabled>
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control content-blog" disabled>{{ old('content', $blog->content) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary d-none" id="saveButton">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.tiny.cloud/1/qmsq1hga0tygul287yejg9t6gpfa5npa36c0ezchh4zom7x1/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editToggle = document.getElementById('editToggle');
            const saveButton = document.getElementById('saveButton');
            const blogForm = document.getElementById('blogForm');
            const inputs = blogForm.querySelectorAll('input, textarea, select');
            const imageInput = document.getElementById('image');

            // Initialize TinyMCE
            tinymce.init({
                selector: 'textarea.content-blog',
                height: 300,
                menubar: false,
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                setup: function(editor) {
                    editor.setMode('readonly'); // start readonly
                    editor.on('change', function() {
                        editor.save();
                    });
                    window.tinyMCEInstance = editor; // save global ref
                }
            });

            // Toggle edit mode
            if (editToggle) {
                editToggle.addEventListener('change', function() {
                    const enable = editToggle.checked;

                    // Enable/disable normal inputs
                    inputs.forEach(input => {
                        if (input !== imageInput && input.name !== '_token' && input.name !==
                            '_method') {
                            input.disabled = !enable;
                        }
                    });

                    // Enable/disable file input manually
                    imageInput.disabled = !enable;

                    // Toggle TinyMCE
                    if (window.tinyMCEInstance) {
                        window.tinyMCEInstance.setMode(enable ? 'design' : 'readonly');
                    }

                    // Toggle save button
                    saveButton.classList.toggle('d-none', !enable);
                });
            }
        });
    </script>
@endpush
