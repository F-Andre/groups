<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class WelcomeController extends Controller
{
  public function index()
  {
    $cookiesAccept = Cookie::get('cookiesAccept');
    if ($cookiesAccept =! true && $cookiesAccept =! false )
    {
      $cookiesAccept = false;
    }

    if (Auth::check()) {
      return redirect(route('group.index', $cookiesAccept));
    }

    return view('welcome', ['cookiesAccept' => $cookiesAccept]);
  }
}
