@extends('layouts.app')
@section('title', 'Quản Lý Người Dùng')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Người Dùng</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Quản Lý Người Dùng</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <form class="form-inline" method="GET" action="{{ route('users.index') }}">
                            <div class="col-md-2">
                                <input type="text" class="form-control w-100" name="search" placeholder="Tìm kiếm mã/tên/email" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit">Tìm Kiếm</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Xóa Bộ Lọc</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Điện Thoại</th>
                                    <th>Khoa</th>
                                    <th>Vai Trò</th>
                                    @if(auth()->user()->role == "admin")
                                        <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="badge badge-primary">{{ $user->code }}</span></td>
                                        <td>{{ $user->name }}</td>
                                        <td><span class="badge badge-info">{{ $user->email }}</span></td>
                                        <td><span class="badge badge-dark">{{ $user->phone ?? 'N/A' }}</span></td>
                                        <td><span class="badge badge-warning">{{ $user->department ?? 'N/A' }}</span></td>
                                        <td>
                                            <span class="badge {{ $user->role == 'admin' ? 'badge-danger' : 'badge-success' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->role == "admin")
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa người dùng này?')">
                                                        <i class="fa-solid fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-end">
                            {!! $users->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
