<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', "%$search%")
                             ->orWhere('name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
            })
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(){
        return view('users.create');
    }

    /**
     * Lưu người dùng mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:users,code',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,lecturer',
            'password' => 'required|string|min:4',
        ], [
            'code.required' => 'Mã người dùng không được để trống.',
            'code.unique' => 'Mã người dùng đã tồn tại.',
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.max' => 'Số điện thoại không được vượt quá 255 ký tự.',
            'department.max' => 'Khoa không được vượt quá 255 ký tự.',
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 4 ký tự.'
        ]);

        User::create([
            'code' => $request->code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công.');
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,lecturer',
            'password' => 'nullable|string|min:4',
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'phone.max' => 'Số điện thoại không được vượt quá 255 ký tự.',
            'department.max' => 'Khoa không được vượt quá 255 ký tự.',
            'birth_date.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'role.required' => 'Vai trò không được để trống.',
            'role.in' => 'Vai trò không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 4 ký tự.'
        ]);

        // Chặn admin tự đổi quyền của mình về lecturer
        if ($authUser->id === $user->id && $authUser->role === 'admin' && $request->role !== 'admin') {
            return back()->withErrors(['role' => 'Bạn không thể thay đổi quyền của chính mình.']);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'department' => $request->department,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'address' => $request->address,
            'role' => $request->role,
            'email' => $request->email
        ]);

        // Nếu có nhập mật khẩu mới thì cập nhật
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.edit', $id)->with('success', 'Cập nhật thông tin người dùng thành công.');
    }

    /**
     * Xóa người dùng.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // Không cho phép admin tự xóa chính mình
        if ($authUser->id === $user->id) {
            return back()->withErrors(['error' => 'Bạn không thể xóa chính mình.']);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công.');
    }
}
