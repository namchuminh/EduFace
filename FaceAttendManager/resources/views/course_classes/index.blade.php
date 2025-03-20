@extends('layouts.app')

@section('title', 'Quản Lý Lớp Học Phần')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Lớp Học Phần</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Quản Lý Lớp Học Phần</li>
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
                        <form class="form-inline" method="GET" action="{{ route('course_classes.index') }}">
                            <div class="col-md-2">
                                <input type="text" class="form-control w-100" name="search" placeholder="Tìm kiếm mã lớp/giảng viên" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit">Lọc / Tìm Kiếm</button>
                                <a href="{{ route('course_classes.index') }}" class="btn btn-secondary">Xóa Bộ Lọc</a>
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
                                    <th>Mã Lớp</th>
                                    <th>Môn Học</th>
                                    <th>Giảng Viên</th>
                                    <th>Học Kỳ</th>
                                    <th>Năm Học</th>
                                    <th>Số SV Đã ĐK</th>
                                    <th>Quản Lý Sinh Viên</th>
                                    @if(auth()->user()->role == "admin")
                                        <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courseClasses as $class)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="badge badge-primary">{{ $class->class_code }}</span></td>
                                        <td>{{ $class->subject->name }}</td>
                                        <td>{{ $class->lecturer->name }}</td>
                                        <td><span class="badge badge-success">{{ $class->semester }}</span></td>
                                        <td><span class="badge badge-info">{{ $class->academic_year }}</span></td>
                                        <td><span class="badge badge-warning">{{ $class->student_count }} Sinh Viên</span></td>
                                        <td>
                                            <!-- Nút Đăng Ký Sinh Viên -->
                                            <a href="{{ route('class_registrations.create', $class->id) }}" class="btn btn-success">
                                                <i class="fa-solid fa-user-plus"></i> Đăng Ký SV
                                            </a>

                                            <!-- Nút Xem DS Sinh Viên -->
                                            <a href="{{ route('class_registrations.index', $class->id) }}" class="btn btn-info">
                                                <i class="fa-solid fa-list"></i> Xem DS SV
                                            </a>
                                        </td>
                                        <td>
                                            @if(auth()->user()->role == "admin")
                                                <!-- Nút Sửa -->
                                                <a href="{{ route('course_classes.edit', $class->id) }}" class="btn btn-warning">
                                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                                </a>

                                                <!-- Nút Xóa -->
                                                <form action="{{ route('course_classes.destroy', $class->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa lớp học phần này?')">
                                                        <i class="fa-solid fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-end">
                            {!! $courseClasses->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
