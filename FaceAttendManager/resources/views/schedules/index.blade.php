@extends('layouts.app')

@section('title', 'Danh Sách Lịch Học')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh Sách Lịch Học</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Lịch Học</li>
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
                        <form action="{{ route('schedules.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control mr-2 col-md-2" placeholder="Tên lớp học phần hoặc tên phòng" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </form>
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
                                    <th>Lớp Học Phần</th>
                                    <th>Giảng Viên</th>
                                    <th>Ngày</th>
                                    <th>Thời Gian</th>
                                    <th>Phòng</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->courseClass->class_code }} - {{ $schedule->courseClass->subject->name }}</td>
                                        <td><span class="badge badge-success">{{ $schedule->courseClass->lecturer->name }}</span> <span class="badge badge-warning">{{ $schedule->courseClass->lecturer->code }}</span></td>
                                        <td><span class="badge badge-info">{{ $schedule->date }}</span></td>
                                        <td><span class="badge badge-dark">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span></td>
                                        <td><span class="badge badge-primary">{{ $schedule->room ?? 'Chưa có' }}</span></td>
                                        <td>
                                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-end">
                            {!! $schedules->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
