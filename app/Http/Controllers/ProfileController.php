<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Hiển thị trang profile
    public function edit()
    {
        return view('profile.edit');
    }

    // Cập nhật thông tin
    public function update(Request $request)
    {
        $user = $request->user();

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload avatar nếu có
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu tồn tại
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Cập nhật thông tin thành công!');
    }
}
