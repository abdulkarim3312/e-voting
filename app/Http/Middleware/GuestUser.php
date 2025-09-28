<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if (session()->has('employee_id')) {
            return redirect('/user/dashboard')->with('info', 'আপনি ইতিমধ্যেই লগইন করেছেন।');
        }

        
        return $next($request);
    }
}
