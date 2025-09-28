<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class User
{
    public function handle(Request $request, Closure $next): Response
    {
       
        if (!session()->has('employee_id')) {
            return redirect('/user/login')->with('info', 'আপনি ইতিমধ্যেই লগইন করেছেন।');
        }

        return $next($request);
    }
}
