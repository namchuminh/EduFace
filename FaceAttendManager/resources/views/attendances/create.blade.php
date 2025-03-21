@extends('layouts.app')

@section('title', 'Điểm Danh')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Điểm Danh</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang Chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendances.select') }}">Điểm Danh</a></li>
                    <li class="breadcrumb-item active">{{ $schedule->courseClass->class_code }} - {{ $schedule->courseClass->subject->name }}
                                        | {{ $schedule->date }} | {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        | {{ $schedule->room }} | {{ $schedule->courseClass->lecturer->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Cột 1: Mở Camera -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Mở Camera</h3>
                    </div>
                    <div class="card-body text-center">
                        <video id="camera" autoplay playsinline style="width: 100%; max-width: 100%;"></video>
                        <canvas id="canvas" style="display: none;"></canvas>

                        <div class="text-center mt-3">
                            <button id="capture-btn" class="btn btn-primary">📸 Điểm Danh</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột 2: Hiển thị Thông Tin Sinh Viên -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Thông Tin Sinh Viên</h3>
                    </div>
                    <div class="card-body">
                        <div id="result" class="text-center p-3">
                            <div class="card border-success shadow-sm p-1">
                                <h5 class="text-success fw-bold" id="noti" style="display: none;">✅ Nhận diện thành công!</h5>
                                <img id="student-photo" src="" class="rounded shadow-sm mt-3" style="margin-left: auto; margin-right: auto; width: 200px; height: 200px; object-fit: cover; border: 2px solid #28a745;" alt="Ảnh sinh viên">
                                
                                <table class="table table-bordered mt-4">
                                    <tbody>
                                        <tr>
                                            <th>Mã sinh viên:</th>
                                            <td id="student-msv">Chưa rõ</td>
                                        </tr>
                                        <tr>
                                            <th>Họ tên:</th>
                                            <td id="student-name">Chưa rõ</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td id="student-email">Chưa rõ</td>
                                        </tr>
                                        <tr>
                                            <th>Lớp:</th>
                                            <td id="student-class">Chưa rõ</td>
                                        </tr>
                                        <tr>
                                            <th>Thời gian:</th>
                                            <td id="attendance-time">Chưa rõ</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <a id="confirm-btn" class="btn btn-primary w-100 mt-2 text-white" style="display: none;">Xác Nhận Điểm Danh</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    #result .table th {
        width: 40%;
        background: #f8f9fa;
        text-align: right;
        font-weight: bold;
    }
    #result img {
        transition: transform 0.3s ease-in-out;
    }
    #result img:hover {
        transform: scale(1.1);
    }
</style>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const video = document.getElementById("camera");
        const canvas = document.getElementById("canvas");
        const captureBtn = document.getElementById("capture-btn");
        const resultDiv = document.getElementById("result");

        // Mở camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(error => {
                console.error("Không thể truy cập camera", error);
                resultDiv.innerHTML = `<p class="text-danger">❌ Không thể mở camera!</p>`;
            });

        // Khi nhấn nút "Điểm Danh"
        captureBtn.addEventListener("click", function() {
            const context = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL("image/jpeg");

            // Chuyển ảnh thành Blob
            canvas.toBlob(blob => {
                const formData = new FormData();
                formData.append("image", blob, "snapshot.jpg");

                // Gửi ảnh lên Flask
                fetch("http://127.0.0.1:5000/predict-face", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.msv) {
                        fetch(`{{ route('students.info') }}?student_code=${data.msv}`, {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                            }
                        })
                        .then(response => response.json())
                        .then(student => {
                            document.getElementById("student-photo").src = imageData;
                            document.getElementById("student-msv").textContent = data.msv;
                            document.getElementById("student-name").textContent = student.name;
                            document.getElementById("student-class").textContent = student.class;
                            document.getElementById("student-email").textContent = student.email;
                            document.getElementById("attendance-time").textContent = new Date().toLocaleString();
                            
                            document.getElementById("confirm-btn").style.display = "block";
                            document.getElementById("noti").style.display = "block";
                            
                            let confirmBtn = document.getElementById("confirm-btn");
                            let scheduleId = "{{ $schedule->id }}"; // Lấy ID lịch học từ Laravel Blade
                            let url = "{{ route('attendances.confirm') }}" + "?student_code=" + data.msv + "&schedule_id=" + '{{ $schedule->id }}';
                            confirmBtn.href = url;

                        })
                        .catch(error => console.error("Lỗi:", error));
                    } else {
                        resultDiv.innerHTML = `<p class="text-danger">❌ Không nhận diện được sinh viên</p>`;
                    }
                })
                .catch(error => {
                    console.error("Lỗi:", error);
                    resultDiv.innerHTML = `<p class="text-danger">❌ Lỗi gửi ảnh!</p>`;
                });
            }, "image/jpeg");
        });
    });
</script>
@endsection