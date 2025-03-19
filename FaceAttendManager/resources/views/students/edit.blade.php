@extends('layouts.app')
@section('title', 'Chỉnh Sửa Sinh Viên')
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
                    <li class="breadcrumb-item"><a href="{{ route('students.index') }}"></a>Quản Lý Sinh Viên</a></li>
                    <li class="breadcrumb-item active">Chỉnh Sửa Sinh Viên</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Chỉnh Sửa Sinh Viên</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('students.update', $student->id) }}">
                    @csrf
                    @method('PUT')

                    @foreach([
                        'student_code' => ['Mã Sinh Viên', 'Nhập mã sinh viên (VD: SV12345)'], 
                        'name' => ['Tên Sinh Viên', 'Nhập tên sinh viên (VD: Nguyễn Văn A)'], 
                        'email' => ['Email', 'Nhập email (VD: email@example.com)'], 
                        'phone' => ['Số Điện Thoại', 'Nhập số điện thoại (VD: 0987654321)'], 
                        'birth_date' => ['Ngày Sinh', 'Nhập ngày sinh (VD: 2000-01-01)'], 
                        'address' => ['Địa Chỉ', 'Nhập địa chỉ sinh viên (VD: Số 10, Quận 1, TP.HCM)'], 
                        'department' => ['Khoa', 'Nhập tên khoa (VD: Công Nghệ Thông Tin)'], 
                        'class' => ['Lớp', 'Nhập tên lớp (VD: CNTT01)']
                    ] as $field => [$label, $placeholder])
                        <div class="form-group">
                            <label>{{ $label }}</label>
                            <input type="{{ $field == 'birth_date' ? 'date' : 'text' }}" 
                                   class="form-control @error($field) is-invalid @enderror" 
                                   name="{{ $field }}" 
                                   value="{{ old($field, $student->$field) }}" 
                                   placeholder="{{ $placeholder }}">
                            @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label>Giới Tính</label>
                        <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-right">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Quay Lại</a>  
                        <button type="submit" class="btn btn-primary">Lưu Thông Tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
