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

    // Duyệt tăng ca
    public function approveOvertime(Request $request)
    {
        $attendance = Attendance::findOrFail($request->attendance_id);

        if($attendance->overtime_hours && $attendance->overtime_status != 'Đã duyệt') {
            $attendance->update([
                'overtime_status' => 'Đã duyệt'
            ]);
            return back()->with('success', '⏱️ Tăng ca đã được duyệt!');
        }

        return back()->with('error', '⚠️ Không có tăng ca để duyệt hoặc đã duyệt.');
    }
}
