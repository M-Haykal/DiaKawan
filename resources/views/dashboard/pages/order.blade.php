@extends('dashboard.layouts.index')

@section('title', 'Manage Order')
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
            <h1 class="h3 mb-0 text-gray-800">Manage Order</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Order DiaKawan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Adress</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Note Order</th>
                                <th scope="col">Status Order</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->id }}</th>
                                    <td>{{ $order->user->username }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->total_price }}</td>
                                    <td>{{ $order->note_order }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->payment }}</td>
                                    <td>
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-control form-control-sm"
                                                onchange="this.form.submit()">
                                                <option value="pending"
                                                    {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing"
                                                    {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                                                </option>
                                                <option value="delivered"
                                                    {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                                                </option>
                                                <option value="arrived"
                                                    {{ $order->status == 'arrived' ? 'selected' : '' }}>Arrived</option>
                                                <option value="canceled"
                                                    {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('style')
    <style>

    </style>
@endpush



@push('script')
    <script></script>
@endpush
