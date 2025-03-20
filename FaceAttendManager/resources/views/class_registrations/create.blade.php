@extends('layouts.app')

@section('title', 'Đăng Ký Sinh Viên')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Đăng Ký Sinh Viên Vào Lớp</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('course_classes.index') }}">Lớp Học Phần</a></li>
                    <li class="breadcrumb-item active">Đăng Ký Sinh Viên</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>{{ $courseClass->class_code }} - {{ $courseClass->subject->name }}</b></h3>
            </div>

            <form action="{{ route('class_registrations.store', $courseClass->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="student_id">Chọn Sinh Viên</label>
                        <select name="student_id" id="student_id" class="form-control select2">
                            <option value="">-- Chọn Sinh Viên --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_code }} - {{ $student->name }}</option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-right">
                        <a href="{{ route('class_registrations.index', $courseClass->id) }}" class="btn btn-secondary">Quay Lại</a>
                        <button type="submit" class="btn btn-primary">Đăng Ký</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</section>
@endsection
