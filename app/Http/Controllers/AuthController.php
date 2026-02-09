<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register', [
            'title' => 'BambiBlog · Đăng ký',
        ]);
    }

    public function showLogin()
    {
        return view('auth.login', [
            'title' => 'BambiBlog · Đăng nhập',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = 'images/avatar/default-avatar.png';
        if ($request->hasFile('avatar')) {
            $avatarDir = public_path('images/avatar');
            File::ensureDirectoryExists($avatarDir);

            $file = $request->file('avatar');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $avatarPath = 'images/avatar/'.$filename;
        }

        $user = new User();

        if (Schema::hasColumn('users', 'user_name')) {
            $user->user_name = $validated['user_name'];
        }

        if (Schema::hasColumn('users', 'name')) {
            $user->name = $validated['user_name'];
        }

        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        if (Schema::hasColumn('users', 'avatar')) {
            $user->avatar = $avatarPath;
        }

        if (Schema::hasColumn('users', 'role') && empty($user->role)) {
            $user->role = 'user';
        }

        if (Schema::hasColumn('users', 'status') && $user->status === null) {
            $user->status = 1;
        }

        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
    }
}
