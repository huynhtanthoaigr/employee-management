<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\ScheduleRequest;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // Trang check nghỉ
    public function requestForm(Request $request) {
        $weekStart = $request->input('week_start') 
            ? Carbon::parse($request->input('week_start')) 
            : Carbon::now()->startOfWeek();

        // Tạo mảng 7 ngày liên tục từ week_start
        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[] = $weekStart->copy()->addDays($i)->toDateString();
        }

        $employees = User::all();

        return view('admin.schedule.requests', compact('employees','week','weekStart'));
    }

    // Lưu yêu cầu nghỉ
    public function saveRequests(Request $request) {
        $weekStart = Carbon::parse($request->input('week_start'));

        $data = $request->input('requests', []);
        foreach ($data as $employee_id => $days) {
            foreach ($days as $date => $status) {
                ScheduleRequest::updateOrCreate(
                    ['employee_id'=>$employee_id,'date'=>$date],
                    ['status'=>$status]
                );
            }
        }

        // Đánh dấu đã lưu cho tuần này
        session(['schedule_saved_'.$weekStart->format('Y-m-d') => true]);

        return back()
            ->with('success','Cập nhật yêu cầu nghỉ thành công.')
            ->with('week_start', $weekStart->format('Y-m-d'));
    }

    // Tạo lịch tự động
   public function generateSchedule(Request $request) {
    $weekStart = Carbon::parse($request->input('week_start'));

    // Kiểm tra đã lưu yêu cầu nghỉ chưa
    if (!session('schedule_saved_'.$weekStart->format('Y-m-d'))) {
        return back()
            ->with('error', 'Bạn phải lưu yêu cầu nghỉ trước khi tạo lịch tự động!')
            ->with('week_start', $weekStart->format('Y-m-d'));
    }

    $week = [];
    for ($i = 0; $i < 7; $i++) {
        $week[] = $weekStart->copy()->addDays($i)->toDateString();
    }

    $shifts = Shift::all(); // ca sáng = $shifts[0], ca chiều = $shifts[1]

    $managers = User::whereIn('role',['quanly','truongca'])->get();
    $others = User::whereNotIn('role',['quanly','truongca'])->get();

    // Xóa lịch cũ tuần này
    Schedule::whereBetween('date', [$weekStart, $weekStart->copy()->addDays(6)])->delete();

   foreach ($week as $index => $day) {
    $prevWeekDay = Carbon::parse($day)->subWeek()->toDateString();

    // Tạo danh sách tất cả quản lý và trưởng ca
    $managersAll = $managers->sortBy('id'); // sắp xếp cố định để xoay ca
    $shiftIndex = 0;

    foreach ($managersAll as $manager) {
        // Lấy lịch tuần trước để đảo ca
        $prevSchedule = Schedule::where('employee_id', $manager->id)
                                ->where('date', $prevWeekDay)
                                ->first();
        if ($prevSchedule) {
            $shift = ($prevSchedule->shift_id == $shifts[0]->id) ? $shifts[1] : $shifts[0];
        } else {
            // Nếu chưa có tuần trước, xoay xen kẽ
            $shift = ($index + $shiftIndex) % 2 == 0 ? $shifts[0] : $shifts[1];
            $shiftIndex++;
        }

        Schedule::create([
            'employee_id' => $manager->id,
            'date' => $day,
            'shift_id' => $shift->id
        ]);
    }

    // Phần nhân viên khác giữ nguyên logic hiện tại
    $grouped = $others->groupBy('role');
    foreach ($grouped as $role => $emps) {
        $shiftIndex = 0;
        foreach ($emps as $emp) {
            $req = ScheduleRequest::where('employee_id',$emp->id)
                    ->where('date',$day)->first();
            $status = $req->status ?? 'present';

            if ($status == 'full_day_off') continue;
            if ($status == 'morning_off') {
                Schedule::create([
                    'employee_id' => $emp->id,
                    'date' => $day,
                    'shift_id' => $shifts[1]->id
                ]);
            } elseif ($status == 'afternoon_off') {
                Schedule::create([
                    'employee_id' => $emp->id,
                    'date' => $day,
                    'shift_id' => $shifts[0]->id
                ]);
            } else {
                $prevSchedule = Schedule::where('employee_id', $emp->id)
                                        ->where('date', Carbon::parse($day)->subWeek()->toDateString())
                                        ->first();
                if($prevSchedule) {
                    $shift = ($prevSchedule->shift_id == $shifts[0]->id) ? $shifts[1] : $shifts[0];
                } else {
                    $shift = ($index + $shiftIndex) % 2 == 0 ? $shifts[0] : $shifts[1];
                    $shiftIndex++;
                }

                Schedule::create([
                    'employee_id' => $emp->id,
                    'date' => $day,
                    'shift_id' => $shift->id
                ]);
            }
        }
    }
}

    return back()
        ->with('success','Lịch tuần đã được tạo tự động!')
        ->with('week_start', $weekStart->format('Y-m-d'));
}



    // Xem lịch tuần
   public function viewSchedule(Request $request) {
    $weekStart = $request->input('week_start');

    if ($weekStart) {
        $weekStart = Carbon::parse($weekStart)->startOfWeek();
    } else {
        // Tìm tuần gần nhất có lịch, nếu không có thì tuần hiện tại
        $latestSchedule = Schedule::orderBy('date', 'desc')->first();
        $weekStart = $latestSchedule 
            ? Carbon::parse($latestSchedule->date)->startOfWeek() 
            : Carbon::now()->startOfWeek();
    }

    $week = collect();
    for ($i=0; $i<7; $i++) {
        $week->push($weekStart->copy()->addDays($i)->toDateString());
    }

    $schedules = Schedule::with('employee','shift')
                 ->whereBetween('date',[$week->first(),$week->last()])
                 ->get();

    if ($schedules->isEmpty()) {
        return view('admin.schedule.index', [
            'schedules' => $schedules,
            'week' => $week,
            'weekStart' => $weekStart,
            'message' => '⛔ Tuần này chưa có lịch làm việc hoặc chưa được tạo.'
        ]);
    }

    return view('admin.schedule.index', compact('schedules','week','weekStart'));
}

}
