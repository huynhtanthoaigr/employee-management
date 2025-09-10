<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleRequest;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use App\Notifications\NewScheduleRequest;

class ScheduleRequestController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->input('week_start')
            ? Carbon::parse($request->input('week_start'))->startOfWeek()
            : Carbon::now()->startOfWeek();

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $weekStart->copy()->addDays($i)->toDateString();
        }

        $requests = ScheduleRequest::where('employee_id', Auth::id())
            ->whereBetween('date', [$weekStart, $weekStart->copy()->addDays(6)])
            ->get()
            ->keyBy('date');

        return view('user.schedule.requests', compact('week', 'weekStart', 'requests'));
    }

    public function save(Request $request)
    {
        $weekStart = Carbon::parse($request->input('week_start'));
        $data = $request->input('requests', []);

        foreach ($data as $date => $status) {
            $scheduleRequest = ScheduleRequest::updateOrCreate(
                ['employee_id' => Auth::id(), 'date' => $date],
                ['status' => $status]
            );

            // Gửi thông báo cho admin
            $admins = User::where('role', 'quanly')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewScheduleRequest($scheduleRequest));
            }
        }

        return back()->with('success', 'Đăng ký nghỉ đã được lưu thành công và admin đã được thông báo.');
    }
}
