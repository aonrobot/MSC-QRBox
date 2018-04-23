<?php

namespace App\Http\Middleware;

use Closure;

use Crypt;

class BasicAuth
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
        $user_basic = Crypt::encryptString($_SERVER['PHP_AUTH_USER']);

        $request->session()->put('basic-auth', $user_basic);
        
        return $next($request);
    }
}
