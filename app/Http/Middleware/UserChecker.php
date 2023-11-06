<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChecker
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
        if(Auth::check()){
            if(Auth::user()->status == 'off'){ // logout user if account suspended || khoroj karabar agar hsab masdod bod
                return redirect('logout')->with('error',__('messages.not_active'));
            }

            // if(Auth::user()->roles[0]->name == 'Super admin'){ // logout user if account suspended || khoroj karabar agar hsab masdod bod
            //     return redirect('logout')->with('error',__('messages.unavailable'));
            // }
        }
        return $next($request);
    }
}
