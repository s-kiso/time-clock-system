<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLoginForm()
    {
        return view('admin/login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // attemptメソッドは入力したパスワード($password)を自動的にハッシュして比較する
        if(Auth::attempt(['email' => $email, 'password' => $password, 'admin_check' => 1])){
            $request->session()->regenerate();
            return redirect()->intended('/admin/list');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません'
        ])->onlyInput('email');
    }

    
}
