<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Attendance;

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

        $schedules = $query->paginate(10);

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

        // Lấy thông tin sinh viên
        $student = Student::where('student_code', $student_code)->first();

        if (!$student) {
            return redirect()->route('attendances.index')->with('error', 'Sinh viên không tồn tại!');
        }

        // Kiểm tra xem sinh viên đã điểm danh hay chưa
        $existingAttendance = Attendance::where('student_id', $student->id)
                                        ->where('schedule_id', $schedule_id)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('error', 'Sinh viên đã điểm danh trước đó!');
        }

        // Thêm bản ghi điểm danh mới
        Attendance::create([
            'student_id'  => $student->id,
            'schedule_id' => $schedule_id,
            'checked_at'  => now(),
            'status'      => 'present',
        ]);

        return redirect()->route('attendances.mark', ['schedule_id' => $schedule_id])->with('success', 'Điểm danh thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
