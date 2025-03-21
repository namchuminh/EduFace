<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\ClassRegistration;

class AttendanceController extends Controller
{
    /**
     * Thông tin lịch học có điểm danh
     */
    public function index(Request $request)
    {
        $query = Schedule::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('room', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('courseClass', function ($q) use ($request) {
                    $q->where('class_code', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('subject', function ($q2) use ($request) {
                        $q2->where('name', 'LIKE', '%' . $request->search . '%');
                    });
                });
        }

        // Sắp xếp theo ngày mới nhất hoặc thời gian tạo mới nhất
        $schedules = $query->orderBy('date', 'desc')->paginate(10);

        return view('attendances.index', compact('schedules'));
    }

    public function select(){
        $schedules = Schedule::all();
        return view('attendances.select', compact('schedules'));
    }

    /**
     * Mở camera và lấy khuân mặt trong camera
     */
    public function mark(Request $request)
    {
        $scheduleId = $request->query('schedule_id'); // Lấy ID từ URL
        $schedule = Schedule::find($scheduleId); // Tìm lịch học theo ID

        if (!$schedule) {
            return redirect()->route('attendances.select')->with('error', 'Không tìm thấy lịch học.');
        }

        return view('attendances.create', compact('schedule')); // Truyền dữ liệu qua view
    }

    /**
     * Xác nhận việc điểm danh
     */
    public function confirm(Request $request)
    {
        $student_code = $request->query('student_code');
        $schedule_id = $request->query('schedule_id');

        // Lấy thông tin sinh viên từ student_code
        $student = Student::where('student_code', $student_code)->first();
        if (!$student) {
            return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('error', 'Không tìm thấy sinh viên.');
        }

        $student = Student::where('student_code', $student_code)->first();

        if (!$student) {
            return response()->json(['error' => 'Sinh viên không tồn tại!'], 404);
        }

        $schedule_class = Schedule::findOrFail($schedule_id);

        $existsStudent = ClassRegistration::where('student_id', $student->id)->where('course_class_id', $schedule_class->course_class_id)->count();

        if ($existsStudent <= 0) {
            return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('error', 'Sinh viên không thuộc lớp này.');
        } 

        // Kiểm tra xem sinh viên đã điểm danh chưa
        $existingAttendance = Attendance::where('student_id', $student->id)
                                        ->where('schedule_id', $schedule_id)
                                        ->where('status', 'present')
                                        ->first();
        if ($existingAttendance) {
            return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('error', 'Sinh viên đã điểm danh trước đó.');
        }

        // Xóa điểm danh trước nếu tồn tại
        Attendance::where('student_id', $student->id)
        ->where('schedule_id', $schedule_id)
        ->delete();


        // Nếu chưa điểm danh, thêm bản ghi mới
        Attendance::create([
            'student_id'  => $student->id,
            'schedule_id' => $schedule_id,
            'checked_at'  => now(),
            'status'      => 'present', // Có mặt
        ]);

        // Lấy danh sách tất cả sinh viên thuộc lớp học phần của lịch giảng đó
        $schedule = Schedule::findOrFail($schedule_id);
        $studentsInClass = ClassRegistration::where('course_class_id', $schedule->courseClass->id)->pluck('student_id');

        // Tạo danh sách sinh viên chưa có trong bảng điểm danh và gán trạng thái 'absent'
        $studentsNotMarked = $studentsInClass->diff(
            Attendance::where('schedule_id', $schedule_id)->pluck('student_id')
        );

        foreach ($studentsNotMarked as $student_id) {
            Attendance::create([
                'student_id'  => $student_id,
                'schedule_id' => $schedule_id,
                'checked_at'  => now(),
                'status'      => 'absent', // Mặc định là vắng mặt nếu chưa điểm danh
            ]);
        }

        return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('success', 'Điểm danh thành công!');
    }

    /**
     * Xem thông tin chi tiết điểm danh
     */
    public function show($schedule_id)
    {
        // Lấy danh sách sinh viên đã điểm danh
        $attendedStudents = Attendance::with('student')->where('schedule_id', $schedule_id)->get();
        $schedule = Schedule::findOrFail($schedule_id); // Tìm lịch học theo ID
        return view('attendances.show', compact('attendedStudents', 'schedule'));
    }

    /**
     * Câp nhật trạng thái điểm danh
     */
    public function updateStatus(Request $request)
    {
        $studentId = $request->input('student_id');
        $scheduleId = $request->input('schedule_id');
        $status = $request->input('status');

        $attendance = Attendance::where('student_id', $studentId)
                                ->where('schedule_id', $scheduleId)
                                ->first();

        if ($attendance) {
            $attendance->update(['status' => $status]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy bản ghi']);
    }
}
