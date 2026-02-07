<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAdmin
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
        return $next($request);
        if(!auth()->user()){
            return redirect()->route('login');
        }
        if(auth()->user()->role_id != 1){
            return redirect()->back()->with('error','غير مسموح لك بالدخول الي هادا الرابط');
        }
        if(auth()->user()->role_id == 1){
            return $next($request);
        }
        return redirect()->back()->with('error','غير مسموح لك بالدخول الي هادا الرابط');
    }
}
