<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
  public function index()
  {

    $id = Cookie::get('user_id');
    $user = User::where('id', $id)->first();
    return view('home', ['user' => $user]);
  }
}
