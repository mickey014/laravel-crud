<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\Student;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        if ($redirect = $this->check_student_loggedin('student', 'students.index')) {
            return $redirect; // Stop execution and redirect
        }

        return view('student.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:191',
            'password' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        }


        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $student = Auth::guard('student')->user();

            if ($remember) {
                session([
                    'remember_username' => $credentials['username'],
                    'remember_password' => Crypt::encryptString($credentials['password'])
                ]);
            } else {
                $student->remember_token = null;
                session()->forget(['remember_username', 'remember_password']);
                $student->update();
            }

            return response()->json([
                'status' => 200,
            ]);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Invalid username or password.',
        ]);
    }

    public function showRegister()
    {
        if ($redirect = $this->check_student_loggedin('student', 'students.index')) {
            return $redirect; // Stop execution and redirect
        }

        return view('student.auth.signup');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'username' => 'required|max:191|unique:students,username',
            'password' => 'required|max:191|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        }

        $student = new Student();
        $student->name = $request->name;
        $student->username = $request->username;
        $student->password = Hash::make($request->password);
        $student->save();

        return response()->json([
            'status' => 200,
            'message' => 'Student was added.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        Session::forget('student');
        return redirect()->route('students.showLogin');
    }


    public function check_student_loggedin($guardName, $route)
    {
        if (Auth::guard($guardName)->check()) {
            return redirect()->route($route); // Redirect if already logged in
        }

        // if (Auth::guard('student')->check()) {
        //     return redirect()->route('students.index'); // Redirect if already logged in
        // }
    }
}
