@extends('layouts.app')

@section('title', 'Thông Tin Điểm Danh')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Thông Tin Điểm Danh</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Điểm Danh</a></li>
                    <li class="breadcrumb-item active">Thông Tin Điểm Danh</li>
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
                        {{ $schedule->courseClass->class_code }} - {{ $schedule->courseClass->subject->name }}
                                        | {{ $schedule->date }} | {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        | {{ $schedule->room }} | {{ $schedule->courseClass->lecturer->name }}
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
                                    <th>Mã SV</th>
                                    <th>Họ Tên</th>
                                    <th>Lớp</th>
                                    <th class="text-center">Đã Điểm Danh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendedStudents as $attended)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $attended->student->student_code }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $attended->student->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $attended->student->class }}</span>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" 
                                                class="attendance-checkbox" 
                                                data-student-id="{{ $attended->student_id }}" 
                                                data-schedule-id="{{ $attended->schedule_id }}" 
                                                {{ $attended->status == "present" ? "checked" : "" }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".attendance-checkbox").forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                let studentId = this.getAttribute("data-student-id");
                let scheduleId = this.getAttribute("data-schedule-id");
                let status = this.checked ? "present" : "absent"; // Có mặt khi checked, vắng khi bỏ tích

                fetch("{{ route('attendances.updateStatus') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        schedule_id: scheduleId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Cập nhật trạng thái thành công");
                    } else {
                        alert("Lỗi cập nhật trạng thái");
                        this.checked = !this.checked; // Hoàn tác nếu có lỗi
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
                    alert("Có lỗi xảy ra");
                    this.checked = !this.checked; // Hoàn tác nếu có lỗi
                });
            });
        });
    });
</script>
@endsection
