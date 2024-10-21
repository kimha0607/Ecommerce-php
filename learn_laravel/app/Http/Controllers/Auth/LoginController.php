<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && password_verify($request->password, $user->password)) {
            Auth::login($user);

            $cookie = Cookie::make('user_id', $user->id, 60 * 24);

            return redirect()->intended('')->cookie($cookie);
        }

        // Trả về lỗi nếu xác thực thất bại
        return back()->withErrors([
            'username' => 'Invalid username or password.',
        ]);
    }

}
