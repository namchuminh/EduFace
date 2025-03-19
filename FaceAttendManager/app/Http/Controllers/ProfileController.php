<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra validate
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'current_password' => 'nullable|required_with:new_password|string|min:4',
            'new_password' => 'nullable|string|min:4|confirmed',
        ], [
            'name.required' => 'Họ và Tên không được để trống.',
            'name.max' => 'Họ và Tên không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 255 ký tự.',
            'department.max' => 'Khoa không được vượt quá 255 ký tự.',
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'current_password.required_with' => 'Vui lòng nhập mật khẩu hiện tại nếu muốn đổi mật khẩu.',
            'current_password.min' => 'Mật khẩu hiện tại phải có ít nhất 4 ký tự.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 4 ký tự.',
            'new_password.confirmed' => 'Mật khẩu mới không khớp.',
        ]);
        

        // Nếu có đổi mật khẩu
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Cập nhật thông tin cá nhân
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'department' => $request->department,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}

