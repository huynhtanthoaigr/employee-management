<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $schedule = Schedule::with('shift')->findOrFail($request->schedule_id);
        $now = Carbon::now();

        if (!$schedule->shift)
            return back()->with('error', 'Ca làm không hợp lệ!');

        $shiftStart = Carbon::parse($schedule->date . ' ' . $schedule->shift->start_time);
        $checkinWindowEnd = $shiftStart->copy()->addMinutes(10);

        if ($now->between($shiftStart, $checkinWindowEnd)) {
            Attendance::updateOrCreate(
                ['schedule_id' => $schedule->id, 'employee_id' => Auth::id()],
                ['date' => $schedule->date, 'check_in' => $now->format('H:i:s')]
            );
            return back()->with('success', '✅ Check-in thành công!');
        }
        return back()->with('error', '⛔ Chưa đến giờ check-in!');
    }

    public function checkOut(Request $request)
    {
        $schedule = Schedule::with('shift')->findOrFail($request->schedule_id);
        $attendance = Attendance::where('schedule_id', $schedule->id)
                                ->where('employee_id', Auth::id())
                                ->firstOrFail();
        $now = Carbon::now();

        $shiftEnd = Carbon::parse($schedule->date . ' ' . $schedule->shift->end_time);
        $checkoutWindowStart = $shiftEnd->copy()->subMinutes(10);

        if ($now->greaterThanOrEqualTo($checkoutWindowStart)) {
            $attendance->update(['check_out' => $now->format('H:i:s')]);
            return back()->with('success', '⏹️ Check-out thành công!');
        }
        return back()->with('error', '⛔ Chưa đến giờ check-out!');
    }

    public function overtime(Request $request)
    {
        $schedule = Schedule::findOrFail($request->schedule_id);
        $attendance = Attendance::where('schedule_id', $schedule->id)
                                ->where('employee_id', Auth::id())
                                ->firstOrFail();

        if (!$attendance->check_in || !$attendance->check_out) {
            return back()->with('error','⛔ Phải hoàn tất check-in và check-out trước khi đăng ký tăng ca!');
        }

        // Lưu số giờ tăng ca và trạng thái chờ duyệt
        $attendance->update([
            'overtime_hours' => $request->hours,
            'overtime_status' => 'Chờ duyệt'
        ]);

        return back()->with('success','⏱️ Đăng ký tăng ca thành công! Chờ duyệt.');
    }
}
