@extends('layouts.app')

@section('title', 'Thêm Lịch Học')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Thêm Lịch Học</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Lịch Học</a></li>
                    <li class="breadcrumb-item active">Thêm Lịch Học</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thêm Lịch Học</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Lớp Học Phần</label>
                        <select name="course_class_id" class="form-control @error('course_class_id') is-invalid @enderror" required>
                            <option value="">-- Chọn lớp học phần --</option>
                            @foreach ($courseClasses as $class)
                                <option value="{{ $class->id }}" {{ old('course_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_code }} - {{ $class->subject->name }} | GV: {{ $class->lecturer->name }} | ID: {{ $class->lecturer->code }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_class_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Ngày</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Giờ Bắt Đầu</label>
                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Giờ Kết Thúc</label>
                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Phòng</label>
                        <input type="text" name="room" class="form-control @error('room') is-invalid @enderror" placeholder="Nhập phòng học" value="{{ old('room') }}">
                        @error('room')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-right">
                        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Quay Lại</a>
                        <button type="submit" class="btn btn-primary">Lưu Thông Tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
