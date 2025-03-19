@extends('layouts.app')
@section('title', 'Quản Lý Môn Học')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản Lý Môn Học</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item active">Quản Lý Môn Học</li>
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
                        <form class="form-inline" method="GET" action="{{ route('subjects.index') }}">
                            <div class="col-md-2">
                                <input type="text" class="form-control w-100" name="search" placeholder="Tìm kiếm mã/tên môn học" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" type="submit">Lọc / Tìm Kiếm</button>
                                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Xóa Bộ Lọc</a>
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
                                    <th>Mã Môn Học</th>
                                    <th>Tên Môn Học</th>
                                    <th>Số Tín Chỉ</th>
                                    <th>Khoa</th>
                                    @if(auth()->user()->role == "admin")
                                        <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="badge badge-primary">{{ $subject->subject_code }}</span></td>
                                        <td>{{ $subject->name }}</td>
                                        <td><span class="badge badge-warning">{{ $subject->credit }} tín chỉ</span></td>
                                        <td><span class="badge badge-danger">{{ $subject->department ?? 'N/A' }}</span></td>
                                        @if(auth()->user()->role == "admin")
                                            <td>
                                                <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-warning">
                                                    <i class="fa-solid fa-pen-to-square"></i> Sửa
                                                </a>
                                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xóa môn học này?')">
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
                            {!! $subjects->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
