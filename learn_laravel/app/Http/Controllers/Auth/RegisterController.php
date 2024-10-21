<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function showRegistrationForm()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'fullname' => 'required|string|max:255',
      'username' => 'required|string|max:255|unique:users',
      'email' => 'required|string|email|max:255',
      'phone_number' => 'required|string|max:255',
      'user_address' => 'required|string|max:255',
      'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    User::create([
      'full_name' => $request->fullname,
      'username' => $request->username,
      'email' => $request->email,
      'phone_number' => $request->phone_number,
      'user_address' => $request->user_address,
      'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Đăng ký thành công! Bạn có thể đăng nhập.');
  }
}

