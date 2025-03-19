<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.index');
    }

    public function get_all_student()
    {
        $students = Student::select('id', 'name', 'email', 'phone', 'course')->latest()->get();
        return response()->json([
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'username' => 'required|max:191|unique:students,username',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $student = new Student();
            $student->name = $request->name;
            $student->username = $request->username;

            $default_pass = env('DEFAULT_PASSWORD');
            $student->password = Hash::make($default_pass);
            $student->save();

            return response()->json([
                'status' => 200,
                'message' => 'Student was added.',
            ]);
        }
    }

    public function show_student(Request $request)
    {
        $studentId = $request->studentId;
        $student = Student::select('id', 'name', 'email', 'phone', 'course', 'username', 'age')->findOrFail($studentId);
        $student->phone = substr($student->phone, 1);

        return response()->json([
            'student' => $student,
        ]);
    }

    public function update_student(Request $request)
    {

        $id = $request->studentId;
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'studentId' => 'nullable|exists:students,id',
            'name' => 'nullable|max:191',
            'username' => 'nullable|max:191|unique:students,username,' . $id,
            'age' => 'nullable|integer',
            'email' => 'nullable|email|max:191|unique:students,email,' . $id,
            'phone' => 'nullable|digits:10',
            'course' => 'nullable|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $student->name = $request->name;
            $student->username = $request->username;
            $student->age = $request->age;
            $student->email = $request->email;
            $student->phone = '0' . $request->phone;
            $student->course = $request->course;
            $student->update();

            return response()->json([
                'status' => 200,
                'message' => 'Student was updated.',
            ]);
        }
    }

    public function delete_student(Request $request)
    {
        $studentId = $request->studentId;
        $student = Student::findOrFail($studentId);
        $student->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Student was deleted.',
        ]);
    }
}
