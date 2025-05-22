<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function showLoginForm() {
        return view('Auth.login');
    }

    public function login(Request $request) {
        $credentials = $request -> only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request -> session() -> regenerate();
            return redirect() -> intended('/dashboard');
        }

        return back() -> withErrors([
            'username' => "Wrong Username or Password"
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        
        $request -> session() -> invalidate();
        $request -> session() -> regenerateToken();

        return redirect() -> route('login');
    }
}
