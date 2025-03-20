@extends('layouts.app')

@section('title', 'Danh Sách Sinh Viên')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh Sách Sinh Viên Đăng Ký</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('course_classes.index') }}">Lớp Học Phần</a></li>
                    <li class="breadcrumb-item active">Danh Sách Sinh Viên</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><b>{{ $courseClass->class_code }} - {{ $courseClass->subject->name }}</b></h2>
                        <div class="card-tools">
                            <a href="{{ route('class_registrations.create', $courseClass->id) }}" class="btn btn-success">
                                <i class="fa-solid fa-user-plus"></i> Đăng Ký Sinh Viên
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã Sinh Viên</th>
                                    <th>Họ Tên</th>
                                    <th>Email</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrations as $registration)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $registration->student->student_code }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $registration->student->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $registration->student->email }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('class_registrations.destroy', [$courseClass->id, $registration->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa sinh viên này khỏi lớp?')">
                                                    <i class="fa-solid fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div class="text-left">
                            <a href="{{ route('course_classes.index') }}" class="btn btn-secondary">Quay Lại</a>
                        </div>
                        <div class="text-right d-flex justify-content-end">
                            {!! $registrations->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
