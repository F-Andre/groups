<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;

class WelcomeController extends Controller
{
  public function index()
  {
    if (Auth::check()) {
      return redirect(route('group.index'));
    }

    return view('welcome');
  }

  public function cookies()
  {
    Cookie::queue(Cookie::make('cookiesAccept', 'true', 262980));
    return redirect()->back();
  }
}
