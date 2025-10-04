@extends('dashboard.layouts.index')

@section('title', 'Manage User')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manage User, Roles & Permissions</h1>
        <ul class="nav nav-fill nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="fill-tab-0" data-bs-toggle="tab" href="#users" role="tab"
                    aria-controls="users" aria-selected="true"> Users </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="fill-tab-1" data-bs-toggle="tab" href="#roles" role="tab" aria-controls="roles"
                    aria-selected="false"> Roles </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="fill-tab-2" data-bs-toggle="tab" href="#permissions" role="tab"
                    aria-controls="permissions" aria-selected="false"> Permissions </a>
            </li>
        </ul>
        <div class="tab-content pt-5" id="tab-content">
            <div class="tab-pane active" id="users" role="tabpanel" aria-labelledby="fill-tab-0">
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Manage Users</h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username ?? $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->pluck('name')->join(', ') ?: 'Belum ada role' }}</td>
                                        <td>
                                            <input type="checkbox" class="toggle-role" data-user="{{ $user->id }}">
                                        </td>
                                    </tr>
                                    <tr id="role-row-{{ $user->id }}" class="role-row" style="display:none;">
                                        <td colspan="5">
                                            <form action="{{ route('dashboard.users.assignRole', $user->id) }}"
                                                method="POST" class="d-flex gap-2">
                                                @csrf
                                                <select name="role" class="form-control" required>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="roles" role="tabpanel" aria-labelledby="fill-tab-1">
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Role & Permissions</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($roles as $role)
                            <div class="border rounded p-3 mb-3">
                                <h6>{{ $role->name }}</h6>
                                <form action="{{ route('dashboard.permissions.assign') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                    <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-3">
                                                <label>
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-success mt-2">Simpan Permission</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="permissions" role="tabpanel" aria-labelledby="fill-tab-2">
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Permission</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.permissions.store') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Nama permission baru"
                                    required>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>

                        <h6 class="mt-4 fw-bold">Daftar Permission:</h6>
                        <ul>
                            @foreach ($permissions as $permission)
                                <li>{{ $permission->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        document.querySelectorAll('.toggle-role').forEach(cb => {
            cb.addEventListener('change', function() {
                let row = document.getElementById("role-row-" + this.dataset.user);
                row.style.display = this.checked ? 'table-row' : 'none';
            });
        });
    </script>
@endpush
