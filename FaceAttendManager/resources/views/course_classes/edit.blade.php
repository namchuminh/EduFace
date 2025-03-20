@extends('layouts.app')

@section('title', 'Chỉnh Sửa Lớp Học Phần')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh Sửa Lớp Học Phần</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('course_classes.index') }}">Quản Lý Lớp Học Phần</a></li>
                    <li class="breadcrumb-item active">Chỉnh Sửa</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh Sửa Thông Tin Lớp Học Phần</h3>
                    </div>

                    <form action="{{ route('course_classes.update', $courseClass->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="class_code">Mã Lớp Học Phần</label>
                                <input type="text" class="form-control @error('class_code') is-invalid @enderror" 
                                    name="class_code" value="{{ old('class_code', $courseClass->class_code) }}" 
                                    placeholder="Nhập mã lớp học phần...">
                                @error('class_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="subject_id">Môn Học</label>
                                <select class="form-control @error('subject_id') is-invalid @enderror" name="subject_id">
                                    <option value="">-- Chọn Môn Học --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $courseClass->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="lecturer_id">Giảng Viên</label>
                                <select class="form-control @error('lecturer_id') is-invalid @enderror" name="lecturer_id">
                                    <option value="">-- Chọn Giảng Viên --</option>
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $courseClass->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                                            {{ $lecturer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lecturer_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="semester">Học Kỳ</label>
                                <input type="text" class="form-control @error('semester') is-invalid @enderror" 
                                    name="semester" value="{{ old('semester', $courseClass->semester) }}" 
                                    placeholder="Nhập học kỳ (ví dụ: HK1, HK2)...">
                                @error('semester')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="academic_year">Năm Học</label>
                                <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                                    name="academic_year" value="{{ old('academic_year', $courseClass->academic_year) }}" 
                                    placeholder="Nhập năm học (ví dụ: 2024-2025)...">
                                @error('academic_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="student_count">Số Lượng Sinh Viên</label>
                                <input type="number" class="form-control @error('student_count') is-invalid @enderror" 
                                    name="student_count" value="{{ old('student_count', $courseClass->student_count) }}" 
                                    placeholder="Nhập số lượng sinh viên...">
                                @error('student_count')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="text-right">
                                <a href="{{ route('course_classes.index') }}" class="btn btn-secondary">Quay Lại</a>
                                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
