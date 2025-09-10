<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Lấy tất cả nhân viên trừ quản lý
        $positions = User::where('role', '!=', 'quanly')
            ->select('role', \DB::raw('count(*) as employee_count'))
            ->groupBy('role')
            ->get();

        // Thêm danh sách nhân viên cho từng vị trí
        foreach ($positions as $position) {
            $position->employees = User::where('role', $position->role)->get();
        }

        // Lấy các thông báo chưa đọc của admin
        $notifications = Auth::user()->unreadNotifications;

        // Truyền biến positions và notifications vào view
        return view('admin.dashboard', compact('positions', 'notifications'));
    }

    // Xóa một thông báo
    public function destroyNotification($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Thông báo đã được xóa.');
        }

        return back()->with('error', 'Không tìm thấy thông báo.');
    }

    // Đánh dấu tất cả đã đọc
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Tất cả thông báo đã được đánh dấu là đã đọc.');
    }
}
