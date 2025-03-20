<?php
namespace App\Http\Controllers;

use App\Models\CourseClass;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $courseClasses = CourseClass::with(['subject', 'lecturer'])
            ->when($search, function ($query, $search) {
                return $query->where('class_code', 'like', "%$search%")
                            ->orWhereHas('lecturer', function ($q) use ($search) {
                                $q->where('name', 'like', "%$search%");
                            });
            })
            ->paginate(10);

        return view('course_classes.index', compact('courseClasses'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $lecturers = User::where('role', 'lecturer')->get(); // Chỉ lấy user có role = lecturer
        return view('course_classes.create', compact('subjects', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_code'     => 'required|string|max:20|unique:course_classes,class_code',
            'subject_id'     => 'required|exists:subjects,id',
            'lecturer_id'    => 'required|exists:users,id',
            'semester'       => 'required|string|max:20',
            'academic_year'  => 'required|string|max:20',
            'student_count'  => 'required|integer|min:0',
        ], [
            'class_code.required'     => 'Mã lớp học phần không được để trống.',
            'class_code.unique'       => 'Mã lớp học phần đã tồn tại.',
            'class_code.max'          => 'Mã lớp học phần không quá 20 ký tự.',
            
            'subject_id.required'     => 'Vui lòng chọn môn học.',
            'subject_id.exists'       => 'Môn học không hợp lệ.',

            'lecturer_id.required'    => 'Vui lòng chọn giảng viên.',
            'lecturer_id.exists'      => 'Giảng viên không hợp lệ.',

            'semester.required'       => 'Học kỳ không được để trống.',
            'semester.max'            => 'Học kỳ không quá 20 ký tự.',

            'academic_year.required'  => 'Năm học không được để trống.',
            'academic_year.max'       => 'Năm học không quá 20 ký tự.',

            'student_count.required'  => 'Số lượng sinh viên không được để trống.',
            'student_count.integer'   => 'Số lượng sinh viên phải là số nguyên.',
            'student_count.min'       => 'Số lượng sinh viên không được âm.',
        ]);

        CourseClass::create($request->all());

        return redirect()->route('course_classes.index')->with('success', 'Thêm lớp học phần thành công.');
    }

    public function show(CourseClass $courseClass)
    {
        return view('course_classes.show', compact('courseClass'));
    }

    public function edit($id)
    {
        $courseClass = CourseClass::findOrFail($id);
        $subjects = Subject::all();
        $lecturers = User::where('role', 'lecturer')->get(); // Chỉ lấy user có role = lecturer

        return view('course_classes.edit', compact('courseClass', 'subjects', 'lecturers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_code'    => 'required|string|max:50|unique:course_classes,class_code,' . $id,
            'subject_id'    => 'required|exists:subjects,id',
            'lecturer_id'   => 'required|exists:users,id',
            'semester'      => 'required|string|max:20',
            'academic_year' => 'required|string|max:20',
            'student_count' => 'nullable|integer|min:0',
        ], [
            'class_code.required'    => 'Mã lớp học phần không được để trống.',
            'class_code.unique'      => 'Mã lớp học phần đã tồn tại, vui lòng chọn mã khác.',
            'class_code.max'         => 'Mã lớp học phần không được vượt quá 50 ký tự.',
            
            'subject_id.required'    => 'Vui lòng chọn môn học.',
            'subject_id.exists'      => 'Môn học không hợp lệ.',
        
            'lecturer_id.required'   => 'Vui lòng chọn giảng viên.',
            'lecturer_id.exists'     => 'Giảng viên không hợp lệ.',
        
            'semester.required'      => 'Học kỳ không được để trống.',
            'semester.max'           => 'Học kỳ không được vượt quá 20 ký tự.',
        
            'academic_year.required' => 'Năm học không được để trống.',
            'academic_year.max'      => 'Năm học không được vượt quá 20 ký tự.',
        
            'student_count.integer'  => 'Số lượng sinh viên phải là số nguyên.',
            'student_count.min'      => 'Số lượng sinh viên không được nhỏ hơn 0.',
        ]);

        $courseClass = CourseClass::findOrFail($id);
        $courseClass->update([
            'class_code'    => $request->class_code,
            'subject_id'    => $request->subject_id,
            'lecturer_id'   => $request->lecturer_id,
            'semester'      => $request->semester,
            'academic_year' => $request->academic_year,
            'student_count' => $request->student_count,
        ]);

        return redirect()->route('course_classes.edit', $id)->with('success', 'Cập nhật lớp học phần thành công.');
    }

    public function destroy(CourseClass $courseClass)
    {
        $courseClass->delete();
        return redirect()->route('course_classes.index')->with('success', 'Lớp học phần đã bị xóa');
    }
}

