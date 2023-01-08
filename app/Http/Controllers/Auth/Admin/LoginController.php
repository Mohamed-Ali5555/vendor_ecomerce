<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('backend.auth.login');
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        if(Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password])){
            // dd('dd');
            return redirect()->intended(route('admin'))->with('success','You are logged in as admin');
        }
        return back()->withInput($request->only('email'));
    }

    
    // public function store(LoginRequestAdmin $request)
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::ADMIN);
    // }

}
