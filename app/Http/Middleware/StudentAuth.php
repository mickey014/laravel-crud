<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentAuth
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
        if (!Auth::guard('student')->check()) {
            return redirect()->route('students.showLogin');
            // return redirect()->route('students.showLogin')->with('error', 'Please log in first.');
        }

        $student = Auth::guard('student')->user();
        Session::put('student', [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'username' => $student->username,
            'phone' => $student->phone,
            'course' => $student->course,
            'age' => $student->age,
            'password' => $student->password,
        ]);

        return $next($request);
    }
}
