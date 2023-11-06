<?php

namespace App\Http\Middleware;

use App\Models\Accounts;
use Closure;
use Illuminate\Http\Request;

class IpChecker
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
        $ip = $request->ip();
        $account = Accounts::where('ip',$ip)->first();
        if($account == ''){
            return response()->json([
                'success' => 'false',
                'message' => 'you do not have access to this resource'
            ]);
        } 
        return $next($request);
    }
}
