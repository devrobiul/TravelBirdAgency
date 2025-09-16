<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   
    public function login()
    {
        return view('auth.login');
    }


    public function loginStore(LoginRequest $request)
{
    $credentials = [
        'phone' => $request->phone,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials)) {
        if (Auth::user()->status != 1) {
            Auth::logout(); 
            return back()->withErrors([
                'phone' => 'Your account is blocked. Please contact support.',
            ])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }
    return back()->withErrors([
        'phone' => 'The provided credentials do not match our records.',
    ])->withInput();
}

}
