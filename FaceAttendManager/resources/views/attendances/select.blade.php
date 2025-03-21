@extends('layouts.app')

@section('title', 'Chọn Lịch Học Điểm Danh')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chọn Lịch Học Điểm Danh</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Điểm Danh</a></li>
                        <li class="breadcrumb-item active">Chọn Lịch Học</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Điểm Danh Theo Lớp Học Phần</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendances.mark') }}" method="GET">
                        <div class="form-group">
                            <label for="schedule_id">Chọn Lịch Giảng</label>
                            <select name="schedule_id" id="schedule_id" class="form-control">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ $schedule->courseClass->class_code }} - {{ $schedule->courseClass->subject->name }}
                                        | {{ $schedule->date }} | {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        | {{ $schedule->room }} | {{ $schedule->courseClass->lecturer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Quay Lại</a>
                            <button type="submit" class="btn btn-primary">Điểm Danh</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection