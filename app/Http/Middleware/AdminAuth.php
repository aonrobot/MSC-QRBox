<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

use App\Member;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = Member::where('loginUser', Auth::user()->getAccountName())->first();
        
        if($role->role !== 'admin'){
            abort(404);
        }

        return $next($request);
    }
}
