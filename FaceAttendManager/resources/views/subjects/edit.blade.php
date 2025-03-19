@extends('layouts.app')
@section('title', 'Chỉnh Sửa Môn Học')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chỉnh Sửa Môn Học</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">Quản Lý Môn Học</a></li>
                    <li class="breadcrumb-item active">Chỉnh Sửa Môn Học</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh sửa thông tin môn học</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('subjects.update', $subject->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Mã Môn Học</label>
                                <input type="text" class="form-control" name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}" required>
                                @error('subject_code') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Tên Môn Học</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $subject->name) }}" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Số Tín Chỉ</label>
                                <input type="number" class="form-control" name="credit" value="{{ old('credit', $subject->credit) }}" required min="1">
                                @error('credit') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Khoa / Bộ Môn</label>
                                <input type="text" class="form-control" name="department" value="{{ old('department', $subject->department) }}">
                                @error('department') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="text-right">
                                <a class="btn btn-secondary" href="{{ route('subjects.index') }}">Quay Lại</a>
                                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
@endsection
