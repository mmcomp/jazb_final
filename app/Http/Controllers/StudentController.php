<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Student;

class StudentController extends Controller
{
    public function index(){
        $students = Student::where('is_deleted', false)
            ->with('user')
            ->with('studenttags.tag')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->orderBy('created_at', 'desc')->get();

        dd($students);
        return view('students.index',[
            'students' => $students,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
}
