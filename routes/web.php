<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentAuthController;


Route::middleware(['student.auth'])->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students.index');
    Route::get('students', [StudentController::class, 'index'])->name('students.index');

    Route::post('logout', [StudentAuthController::class, 'logout'])->name('students.logout');

    Route::get('get-all-student', [StudentController::class, 'get_all_student'])->name('students.all');
    Route::get('show-student', [StudentController::class, 'show_student'])->name('students.show');
    Route::post('students', [StudentController::class, 'store'])->name('students.store');
    Route::put('update-student', [StudentController::class, 'update_student'])->name('students.update');
    Route::delete('delete-student', [StudentController::class, 'delete_student'])->name('students.delete');
});



//Login
Route::get('login', [StudentAuthController::class, 'showLogin'])->name('students.showLogin');
Route::post('login', [StudentAuthController::class, 'login'])->name('students.login');

//Sign Up
Route::get('signup', [StudentAuthController::class, 'showRegister'])->name('students.showSignup');
Route::post('signup', [StudentAuthController::class, 'register'])->name('students.signup');

Route::get('check-auth', function () {
    return response()->json([
        'authenticated' => Auth::guard('student')->check(),
        'user' => Auth::guard('student')->user(),
    ]);
});
