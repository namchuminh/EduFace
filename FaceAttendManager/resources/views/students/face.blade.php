@extends('layouts.app')

@section('title', 'Tải Lên Ảnh Khuôn Mặt')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tải Lên Ảnh Khuôn Mặt</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Tải Lên Ảnh Khuôn Mặt</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Tải Ảnh Khuôn Mặt</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('students.uploadFacePost', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="images">Thông Tin Sinh Viên</label>
                        <input type="text" class="form-control" value="{{ $student->name }} | {{ $student->student_code }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="images">Chọn Ảnh Khuôn Mặt:</label>
                        <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" multiple required>
                        @error('images')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-right">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Quay Lại</a>
                        <button type="submit" class="btn btn-primary">Tải Lên</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
