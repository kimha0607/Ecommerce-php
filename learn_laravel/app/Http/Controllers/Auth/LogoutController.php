<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
  public function index()
  {
    Cookie::queue(Cookie::forget('user_id'));

    return redirect()->route('login');
  }
}
