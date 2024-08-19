<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:administrator|staff')->except('logout');
    }

    public function showAdminLoginForm()
    {
        return view('auth.login');
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->intended($this->redirectTo);
        }

        return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
