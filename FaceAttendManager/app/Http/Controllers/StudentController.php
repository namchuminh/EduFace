<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // Tìm kiếm theo mã sinh viên hoặc tên sinh viên
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('student_code', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
        }

        // Phân trang
        $students = $query->orderBy('id', 'desc')->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_code' => 'required|unique:students,student_code|max:20',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:students,email|max:100',
            'phone' => 'nullable|max:15',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'department' => 'nullable|max:100',
            'class' => 'nullable|max:50',
        ], [
            'student_code.required' => 'Mã sinh viên không được để trống.',
            'student_code.unique' => 'Mã sinh viên đã tồn tại.',
            'student_code.max' => 'Mã sinh viên tối đa 20 ký tự.',
            'name.required' => 'Tên sinh viên không được để trống.',
            'name.max' => 'Tên sinh viên tối đa 100 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'email.max' => 'Email tối đa 100 ký tự.',
            'phone.max' => 'Số điện thoại tối đa 15 ký tự.',
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'address.string' => 'Địa chỉ không hợp lệ.',
            'department.max' => 'Khoa tối đa 100 ký tự.',
            'class.max' => 'Lớp tối đa 50 ký tự.',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Thêm sinh viên thành công!');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'student_code' => "required|max:20|unique:students,student_code,$id",
            'name' => 'required|max:100',
            'email' => "required|email|max:100|unique:students,email,$id",
            'phone' => 'nullable|max:15',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'department' => 'nullable|max:100',
            'class' => 'nullable|max:50',
        ], [
            'student_code.required' => 'Mã sinh viên không được để trống.',
            'student_code.unique' => 'Mã sinh viên đã tồn tại.',
            'student_code.max' => 'Mã sinh viên tối đa 20 ký tự.',
            'name.required' => 'Tên sinh viên không được để trống.',
            'name.max' => 'Tên sinh viên tối đa 100 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'email.max' => 'Email tối đa 100 ký tự.',
            'phone.max' => 'Số điện thoại tối đa 15 ký tự.',
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'address.string' => 'Địa chỉ không hợp lệ.',
            'department.max' => 'Khoa tối đa 100 ký tự.',
            'class.max' => 'Lớp tối đa 50 ký tự.',
        ]);

        $student->update($request->all());

        return redirect()->route('students.edit', $id)->with('success', 'Cập nhật sinh viên thành công!');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return redirect()->route('students.index')->with('success', 'Xóa sinh viên thành công!');
    }
}
