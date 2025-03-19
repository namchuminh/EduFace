<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Hiển thị danh sách môn học.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $subjects = Subject::query()
            ->when($search, function ($query, $search) {
                return $query->where('subject_code', 'LIKE', "%{$search}%")
                            ->orWhere('name', 'LIKE', "%{$search}%");
            })
            ->paginate(10); // Phân trang

        return view('subjects.index', compact('subjects'));
    }
    /**
     * Hiển thị form tạo môn học mới.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Lưu môn học mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'name' => 'required|string|max:100',
            'credit' => 'required|integer|min:1|max:10',
            'department' => 'nullable|string|max:100',
        ], [
            'subject_code.required' => 'Mã môn học không được để trống.',
            'subject_code.max' => 'Mã môn học không được dài quá 20 ký tự.',
            'subject_code.unique' => 'Mã môn học đã tồn tại.',
            'name.required' => 'Tên môn học không được để trống.',
            'name.max' => 'Tên môn học không được dài quá 100 ký tự.',
            'credit.required' => 'Số tín chỉ không được để trống.',
            'credit.integer' => 'Số tín chỉ phải là số nguyên.',
            'credit.min' => 'Số tín chỉ tối thiểu là 1.',
            'credit.max' => 'Số tín chỉ tối đa là 10.',
            'department.max' => 'Tên khoa không được dài quá 100 ký tự.',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Thêm môn học thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết môn học.
     */
    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }

    /**
     * Hiển thị form chỉnh sửa môn học.
     */
    public function edit($id)
    {   
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Cập nhật môn học.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $id,
            'name' => 'required|string|max:100',
            'credit' => 'required|integer|min:1',
            'department' => 'nullable|string|max:100'
        ], [
            'subject_code.required' => 'Mã môn học không được để trống.',
            'subject_code.unique' => 'Mã môn học đã tồn tại.',
            'subject_code.max' => 'Mã môn học tối đa 20 ký tự.',
            'name.required' => 'Tên môn học không được để trống.',
            'name.max' => 'Tên môn học tối đa 100 ký tự.',
            'credit.required' => 'Số tín chỉ không được để trống.',
            'credit.integer' => 'Số tín chỉ phải là số nguyên.',
            'credit.min' => 'Số tín chỉ phải lớn hơn 0.',
            'department.max' => 'Tên khoa tối đa 100 ký tự.'
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.edit', $id)->with('success', 'Cập nhật môn học thành công.');
    }

    /**
     * Xóa môn học.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Xóa môn học thành công!');
    }
}
