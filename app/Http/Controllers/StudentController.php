<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Student;
use App\User;
use App\Source;

class StudentController extends Controller
{
    public function index(){
        $students = Student::where('is_deleted', false)
            ->with('user')
            ->with('studenttags.tag')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->with('consultant')
            ->with('supporter')
            ->orderBy('created_at', 'desc')->get();

        // dd($students);
        return view('students.index',[
            'students' => $students,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $student = new Student();
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $consultantGroupId = Group::getConsultant();
        if($consultantGroupId)
            $consultantGroupId = $consultantGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $consultants = User::where('is_deleted', false)->where('groups_id', $consultantGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        if($request->getMethod()=='GET'){
            return view('students.create', [
                "supports"=>$supports,
                "consultants"=>$consultants,
                "sources"=>$sources,
                "student"=>$student
            ]);
        }

        $student->users_id = Auth::user()->id;
        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->last_year_grade = (int)$request->input('last_year_grade');
        $student->consultants_id = $request->input('consultants_id');
        $student->parents_job_title = $request->input('parents_job_title');
        $student->home_phone = $request->input('home_phone');
        $student->egucation_level = $request->input('egucation_level');
        $student->father_phone = $request->input('father_phone');
        $student->mother_phone = $request->input('mother_phone');
        $student->phone  = $request->input('phone');
        $student->school = $request->input('school');
        $student->average = $request->input('average');
        $student->major = $request->input('major');
        $student->introducing = $request->input('introducing');
        $student->student_phone = $request->input('student_phone');
        $student->sources_id = $request->input('sources_id');
        $student->supporters_id = $request->input('supporters_id');
        $student->save();

        $request->session()->flash("msg_success", "دانش آموز با موفقیت افزوده شد.");
        return redirect()->route('students');
    }

    public function edit(Request $request, $id)
    {
        $student = Student::where('is_deleted', false)->where('id', $id)->first();
        if($student==null){
            $request->session()->flash("msg_error", "دانش آموز مورد نظر پیدا نشد!");
            return redirect()->route('students');
        }
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $consultantGroupId = Group::getConsultant();
        if($consultantGroupId)
            $consultantGroupId = $consultantGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $consultants = User::where('is_deleted', false)->where('groups_id', $consultantGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        if($request->getMethod()=='GET'){
            return view('students.create', [
                "supports"=>$supports,
                "consultants"=>$consultants,
                "sources"=>$sources,
                "student"=>$student
            ]);
        }

        $student->users_id = Auth::user()->id;
        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->last_year_grade = (int)$request->input('last_year_grade');
        $student->consultants_id = $request->input('consultants_id');
        $student->parents_job_title = $request->input('parents_job_title');
        $student->home_phone = $request->input('home_phone');
        $student->egucation_level = $request->input('egucation_level');
        $student->father_phone = $request->input('father_phone');
        $student->mother_phone = $request->input('mother_phone');
        $student->phone  = $request->input('phone');
        $student->school = $request->input('school');
        $student->average = $request->input('average');
        $student->major = $request->input('major');
        $student->introducing = $request->input('introducing');
        $student->student_phone = $request->input('student_phone');
        $student->sources_id = $request->input('sources_id');
        $student->supporters_id = $request->input('supporters_id');
        $student->save();

        $request->session()->flash("msg_success", "دانش آموز با موفقیت بروز شد.");
        return redirect()->route('students');
    }

    public function delete(Request $request, $id)
    {
        $student = Student::where('is_deleted', false)->where('id', $id)->first();
        if($student==null){
            $request->session()->flash("msg_error", "دانش آموز مورد نظر پیدا نشد!");
            return redirect()->route('students');
        }
        $student->is_deleted = true;
        $student->save();

        $request->session()->flash("msg_success", "دانش آموز با موفقیت حذف شد.");
        return redirect()->route('students');
    }
}
