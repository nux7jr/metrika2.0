<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (auth()->check() && $user->two_factor_code != null) {
            if ($user->two_factor_expires_at < now()) {
                return $next($request->route('verify.resend'));
            }
            if (!$request->is('verify*')) {
                if($request->isMethod('get')){
                    return $next($request->route('verify.index'));
                }
                return $next($request->route('verify.store'));
            }
        }
        return $next($request);
    }
}
