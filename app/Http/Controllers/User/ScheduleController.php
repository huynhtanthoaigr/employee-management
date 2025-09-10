<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function viewSchedule(Request $request)
    {
        // Lấy ngày bắt đầu tuần từ request hoặc tuần gần nhất có lịch
        $weekStart = $request->input('week_start');

        if ($weekStart) {
            $weekStart = Carbon::parse($weekStart)->startOfWeek();
        } else {
            $latestSchedule = Schedule::where('employee_id', auth()->id())
                ->orderBy('date', 'desc')
                ->first();
            $weekStart = $latestSchedule
                ? Carbon::parse($latestSchedule->date)->startOfWeek()
                : Carbon::now()->startOfWeek();
        }

        $weekEnd = $weekStart->copy()->endOfWeek();

        // Tạo mảng 7 ngày trong tuần
        $week = collect();
        for ($i = 0; $i < 7; $i++) {
            $week->push($weekStart->copy()->addDays($i)->toDateString());
        }

        // Lấy lịch làm việc tuần hiện tại
        $schedules = Schedule::leftJoin('schedule_requests', function ($join) {
                $join->on('schedules.employee_id', '=', 'schedule_requests.employee_id')
                     ->on('schedules.date', '=', 'schedule_requests.date');
            })
            ->leftJoin('shifts', 'schedules.shift_id', '=', 'shifts.id')
            ->select(
                'schedules.*',
                'shifts.name as shift_name',
                'schedule_requests.status as request_status',
                'schedule_requests.shift_id as request_shift_id'
            )
            ->where('schedules.employee_id', auth()->id())
            ->whereBetween('schedules.date', [$weekStart, $weekEnd])
            ->get();

        // Lấy chấm công của tuần
        $attendances = Attendance::where('employee_id', auth()->id())
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->get();

        $message = null;
        if ($schedules->isEmpty()) {
            $message = '⛔ Tuần này chưa có lịch làm việc hoặc chưa được tạo.';
        }

        return view('user.schedule.index', [
            'schedules' => $schedules,
            'attendances' => $attendances,
            'week' => $week,
            'weekStart' => $weekStart,
            'message' => $message
        ]);
    }
}
