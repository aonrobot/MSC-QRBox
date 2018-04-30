<?php

namespace App\Http\Middleware;

use Closure;

use Crypt;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LdapAuth
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

        if(!Auth::check()){
            return new Response(view('login'));
        }
        
        return $next($request);
    }
}
