@extends('layouts.app')
@section('title', 'Quản Lý Sinh Viên')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Sinh Viên</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Quản Lý Sinh Viên</li>
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
                        <form class="form-inline" method="GET" action="{{ route('students.index') }}">
                            <div class="col-md-2">
                                <input type="text" class="form-control w-100" name="search" placeholder="Tìm kiếm mã/tên sinh viên" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit">Tìm Kiếm</button>
                                <a href="{{ route('students.index') }}" class="btn btn-secondary">Xóa Bộ Lọc</a>
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
                                    <th>Mã Sinh Viên</th>
                                    <th>Họ Tên</th>
                                    <th>Email</th>
                                    <th>Điện Thoại</th>
                                    <th>Khoa</th>
                                    <th>Lớp</th>
                                    <th>Giới Tính</th>
                                    @if(auth()->user()->role == "admin")
                                        <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="badge badge-primary">{{ $student->student_code }}</span></td>
                                        <td>{{ $student->name }}</td>
                                        <td><span class="badge badge-warning">{{ $student->email }}</span></td>
                                        <td><span class="badge badge-info">{{ $student->phone }}</span></td>
                                        <td><span class="badge badge-danger">{{ $student->department ?? 'N/A' }}</span></td>
                                        <td><span class="badge badge-success">{{ $student->class }}</span></td>
                                        <td>
                                            @if($student->gender == 'male')
                                                <span class="badge badge-primary">Nam</span>
                                            @elseif($student->gender == 'female')
                                                <span class="badge badge-pink">Nữ</span>
                                            @else
                                                <span class="badge badge-secondary">Khác</span>
                                            @endif
                                        </td>
                                        
                                        @if(auth()->user()->role == "admin")
                                            <td>
                                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">
                                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                                </a>
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                            {!! $students->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
