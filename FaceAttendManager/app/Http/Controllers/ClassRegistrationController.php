<?php 

namespace App\Http\Controllers;

use App\Models\ClassRegistration;
use App\Models\User;
use App\Models\Student;
use App\Models\CourseClass;
use Illuminate\Http\Request;

class ClassRegistrationController extends Controller
{
    // Hiển thị danh sách sinh viên trong một lớp học phần
    public function index($courseClassId)
    {
        $courseClass = CourseClass::findOrFail($courseClassId);
        $registrations = ClassRegistration::where('course_class_id', $courseClassId)
                        ->with('student')
                        ->paginate(10); 

        return view('class_registrations.index', compact('courseClass', 'registrations'));
    }

    // Hiển thị form đăng ký sinh viên vào lớp học phần
    public function create($courseClassId)
    {
        $courseClass = CourseClass::findOrFail($courseClassId);

        // Lấy danh sách ID sinh viên đã đăng ký vào lớp này
        $registeredStudentIds = ClassRegistration::where('course_class_id', $courseClassId)
                                                ->pluck('student_id')
                                                ->toArray();

        // Lấy danh sách sinh viên chưa đăng ký lớp học phần này
        $students = Student::whereNotIn('id', $registeredStudentIds)->get();

        return view('class_registrations.create', compact('courseClass', 'students'));
    }

    // Xử lý đăng ký sinh viên vào lớp học phần
    public function store(Request $request, $courseClassId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $courseClass = CourseClass::findOrFail($courseClassId);

        // Kiểm tra sinh viên đã đăng ký chưa
        $existingRegistration = ClassRegistration::where([
            'course_class_id' => $courseClassId,
            'student_id' => $request->student_id
        ])->exists();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'Sinh viên này đã đăng ký lớp học phần.');
        }

        // Thêm bản ghi vào class_registrations
        ClassRegistration::create([
            'course_class_id' => $courseClassId,
            'student_id' => $request->student_id
        ]);

        // Cập nhật student_count +1
        $courseClass->increment('student_count');

        return redirect()->route('class_registrations.index', $courseClassId)
                        ->with('success', 'Sinh viên đã được đăng ký vào lớp học phần.');
    }

    // Xóa sinh viên khỏi lớp học phần
    public function destroy($courseClassId, $studentId)
    {
        $courseClass = CourseClass::findOrFail($courseClassId);

        // Kiểm tra bản ghi đăng ký có tồn tại không
        $registration = ClassRegistration::where([
            'course_class_id' => $courseClassId,
            'student_id' => $studentId
        ])->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Sinh viên không tồn tại trong lớp học phần này.');
        }

        // Xóa đăng ký của sinh viên
        $registration->delete();

        // Giảm student_count -1 (nhưng không nhỏ hơn 0)
        $courseClass->decrement('student_count', 1, ['student_count' => max(0, $courseClass->student_count - 1)]);

        return redirect()->route('class_registrations.index', $courseClassId)
                        ->with('success', 'Đã xóa sinh viên khỏi lớp học phần.');
    }
}

