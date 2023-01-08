<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(auth()->user()->role);
        if(Auth::guard('admin')->check()){
            return $next($request);

        }else{
            return redirect()->route('admin.login.form');
        }
        //         if(auth('web')->check()){
        //     return redirect (RouteServiceProvider::HOME);
        // }

        // if(auth('admin')->check()){
        //     return redirect (RouteServiceProvider::ADMIN);
        // }

        return $next($request);
    }
}
