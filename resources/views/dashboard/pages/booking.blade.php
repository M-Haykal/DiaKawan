@extends('dashboard.layouts.index')

@section('title', 'Manage Booking')
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
            <h1 class="h3 mb-0 text-gray-800">Manage Booking</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Manage Booking DiaKawan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Booking Date</th>
                                <th scope="col">Booking Time</th>
                                <th scope="col">Meet Link (Online)</th>
                                <th scope="col">Location</th>
                                <th scope="col">Note</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $booking->name }}</td>
                                    <td>{{ $booking->email }}</td>
                                    <td>{{ $booking->phone }}</td>
                                    <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td>{{ $booking->booking_time->format('H:i') }}</td>
                                    <td>
                                        @if ($booking->meet_link)
                                            <a href="{{ $booking->meet_link }}" target="_blank" class="text-success">
                                                <i class="fas fa-external-link-alt"></i> Link
                                            </a>
                                        @else
                                            <span class="text-muted">Belum diisi</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($booking->location) }}</td>
                                    <td>{{ $booking->note ?? '-' }}</td>
                                    <td>
                                        <!-- Tombol Edit: Buka modal spesifik untuk booking ini -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editBooking{{ $booking->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>

                                <!-- ✅ MODAL DI DALAM LOOP -->
                                <div class="modal fade" id="editBooking{{ $booking->id }}" tabindex="-1"
                                    aria-labelledby="editBookingLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Link Meeting – {{ $booking->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('bookings.edit', $booking) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <!-- Semua field disabled kecuali meet_link -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $booking->name }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control"
                                                            value="{{ $booking->email }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $booking->phone }}" disabled>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Date</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $booking->booking_date->format('d F Y') }}"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Time</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $booking->booking_time->format('H:i') }} WIB"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6"></div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Location</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ ucfirst($booking->location) }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Note</label>
                                                        <textarea class="form-control" rows="2" disabled>{{ $booking->note ?? '-' }}</textarea>
                                                    </div>

                                                    <!-- Hanya field ini yang bisa diedit -->
                                                    <div class="mb-3">
                                                        <label for="meet_link_{{ $booking->id }}" class="form-label">Link
                                                            Google Meet / Zoom</label>
                                                        <input type="url"
                                                            class="form-control @error('meet_link') is-invalid @enderror"
                                                            id="meet_link_{{ $booking->id }}" name="meet_link"
                                                            value="{{ old('meet_link', $booking->meet_link) }}"
                                                            placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                                        @error('meet_link')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Simpan Link</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <!-- Opsional: Tambahkan gaya jika perlu -->
@endpush

@push('script')
    <!-- Bootstrap JS sudah di-load di layout, jadi tidak perlu tambahan -->
@endpush
