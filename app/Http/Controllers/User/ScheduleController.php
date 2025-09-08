<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function viewSchedule(Request $request)
    {
        $weekStart = $request->input('week_start');

        if ($weekStart) {
            $weekStart = Carbon::parse($weekStart)->startOfWeek();
        } else {
            // Tìm tuần gần nhất có lịch, nếu không có thì lấy tuần hiện tại
            $latestSchedule = Schedule::where('employee_id', auth()->id())
                                      ->orderBy('date', 'desc')
                                      ->first();
            $weekStart = $latestSchedule
                ? Carbon::parse($latestSchedule->date)->startOfWeek()
                : Carbon::now()->startOfWeek();
        }

        $weekEnd = $weekStart->copy()->endOfWeek();

        // Tạo 7 ngày trong tuần
        $week = collect();
        for ($i = 0; $i < 7; $i++) {
            $week->push($weekStart->copy()->addDays($i)->toDateString());
        }

        // Lấy lịch làm việc
        $schedules = Schedule::with('shift')
            ->where('employee_id', auth()->id())
            ->whereBetween('date', [$week->first(), $week->last()])
            ->get();

        // Lấy chấm công của tuần
        $attendances = Attendance::where('employee_id', auth()->id())
            ->whereBetween('date', [$week->first(), $week->last()])
            ->get();

        if ($schedules->isEmpty()) {
            return view('user.schedule.index', [
                'schedules' => $schedules,
                'attendances' => $attendances,
                'week' => $week,
                'weekStart' => $weekStart,
                'message' => '⛔ Tuần này chưa có lịch làm việc hoặc chưa được tạo.'
            ]);
        }

        return view('user.schedule.index', compact('schedules', 'attendances', 'week', 'weekStart'));
    }
}
