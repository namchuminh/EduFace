<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\CourseClass;

class ScheduleController extends Controller
{
    /**
     * Hiển thị danh sách lịch học.
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

        $schedules = $query->paginate(10);

        return view('schedules.index', compact('schedules'));
    }

    /**
     * Hiển thị form tạo lịch học mới.
     */
    public function create()
    {
        $courseClasses = CourseClass::all(); // Lấy danh sách lớp học phần
        return view('schedules.create', compact('courseClasses'));
    }

    /**
     * Lưu lịch học mới vào database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ], [
            'course_class_id.required' => 'Vui lòng chọn lớp học phần.',
            'course_class_id.exists' => 'Lớp học phần không hợp lệ.',
            'date.required' => 'Vui lòng chọn ngày học.',
            'date.date' => 'Ngày học không hợp lệ.',
            'date.after_or_equal' => 'Ngày học phải từ hôm nay trở đi.',
            'start_time.required' => 'Vui lòng nhập giờ bắt đầu.',
            'start_time.date_format' => 'Giờ bắt đầu không hợp lệ.',
            'end_time.required' => 'Vui lòng nhập giờ kết thúc.',
            'end_time.date_format' => 'Giờ kết thúc không hợp lệ.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
            'room.string' => 'Phòng học không hợp lệ.',
            'room.max' => 'Phòng học không được quá 255 ký tự.',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Thêm lịch học thành công.');
    }

    /**
     * Hiển thị chi tiết một lịch học.
     */
    public function show($id)
    {
        $schedule = Schedule::with('courseClass')->findOrFail($id);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Hiển thị form chỉnh sửa lịch học.
     */
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $courseClasses = CourseClass::all();
        return view('schedules.edit', compact('schedule', 'courseClasses'));
    }

    /**
     * Cập nhật thông tin lịch học.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'date'            => 'required|date',
            'start_time'      => 'required|date_format:H:i',
            'end_time'        => 'required|date_format:H:i|after:start_time',
            'room'            => 'nullable|string|max:50',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Lịch học đã được cập nhật!');
    }

    /**
     * Xóa lịch học.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Lịch học đã được xóa!');
    }
}
