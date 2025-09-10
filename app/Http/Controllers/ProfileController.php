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

    // Nếu có password mới thì validate và đổi mật khẩu
    if ($request->has('password') && $request->filled('password')) {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('success', '✅ Mật khẩu đã được cập nhật!');
    }

    // Validate thông tin cá nhân
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female,other',
        'avatar' => 'nullable|image|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->gender = $request->gender;

    if ($request->hasFile('avatar')) {
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }
        $user->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', '✅ Thông tin cá nhân đã được cập nhật!');
}

}
