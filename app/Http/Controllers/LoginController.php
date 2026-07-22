<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nickname' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['nickname' => $request->nickname, 'password' => $request->password, 'estado' => 1])) {
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return redirect('login')->with('status', 'Usuario o contraseña incorrectos, intentelo nuevamente');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
