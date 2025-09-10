<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    // Trang danh sách chấm công theo ngày
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        // Lấy tất cả nhân viên
        $employees = User::all();

        // Lấy tất cả lịch + chấm công của ngày đó
        $schedules = Schedule::with('shift', 'attendances')
            ->where('date', $date)
            ->get();

        return view('admin.attendance.index', compact('employees', 'schedules', 'date'));
    }

    // Xem chấm công theo tuần
    public function weekly(Request $request)
    {
        $weekStart = $request->week_start ? Carbon::parse($request->week_start) : Carbon::now()->startOfWeek();

        // 7 ngày trong tuần
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $weekStart->copy()->addDays($i)->format('Y-m-d');
        }

        $employees = User::all();

        // Lấy tất cả chấm công trong tuần
        $attendances = Attendance::with('employee')
            ->whereIn('date', $week)
            ->get();

        return view('admin.attendance.weekly', compact('weekStart', 'week', 'employees', 'attendances'));
    }

    // Chấm công thủ công
    public function markAttendance(Request $request)
    {
        $schedule = Schedule::with('shift')->findOrFail($request->schedule_id);
        $employeeId = $request->employee_id;

        $attendance = Attendance::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'employee_id' => $employeeId
            ],
            [
                'date' => $schedule->date,
                'check_in' => $schedule->shift->start_time,
                'check_out' => $schedule->shift->end_time,
                'status' => 'Đã chấm công'
            ]
        );

        return back()->with('success', '✅ Đã chấm công cho nhân viên.');
    }
    public function deleteAttendance(Request $request)
    {
        $attendance = Attendance::find($request->attendance_id);

        if ($attendance) {
            $attendance->delete();
            return back()->with('success', '❌ Chấm công đã được xóa.');
        }

        return back()->with('error', '⚠️ Không tìm thấy chấm công để xóa.');
    }
    // Thêm chức năng admin nhập tăng ca
    public function addOvertime(Request $request)
    {
        $attendance = Attendance::findOrFail($request->attendance_id);

        $attendance->update([
            'overtime_hours' => $request->overtime_hours,
            'overtime_status' => 'Đã duyệt', // admin nhập trực tiếp
        ]);

        return back()->with('success', "⏱️ Đã cập nhật {$request->overtime_hours} giờ tăng ca cho {$attendance->employee->name}");
    }
    // Duyệt tăng ca
    public function approveOvertime(Request $request)
    {
        $attendance = Attendance::findOrFail($request->attendance_id);

        if ($attendance->overtime_hours && $attendance->overtime_status != 'Đã duyệt') {
            $attendance->update([
                'overtime_status' => 'Đã duyệt'
            ]);
            return back()->with('success', '⏱️ Tăng ca đã được duyệt!');
        }

        return back()->with('error', '⚠️ Không có tăng ca để duyệt hoặc đã duyệt.');
    }
}
