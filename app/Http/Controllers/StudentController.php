<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Student;
use App\User;
use App\Source;
use App\StudentTag;
use App\StudentTemperature;
use App\Tag;
use App\Temperature;

use Exception;

class StudentController extends Controller
{
    public function index(){
        $students = Student::where('is_deleted', false);
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        $supporters_id = null;
        $name = null;
        $sources_id = null;
        $phone = null;
        if(request()->getMethod()=='POST'){
            if(request()->input('supporters_id')!=null){
                $supporters_id = (int)request()->input('supporters_id');
                $students = $students->where('supporters_id', $supporters_id);
            }
            if(request()->input('name')!=null){
                $name = trim(request()->input('name'));
                $students = $students->where(function ($query) use ($name) {
                    $query->where('first_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }
            if(request()->input('sources_id')!=null){
                $sources_id = (int)request()->input('sources_id');
                $students = $students->where('sources_id', $sources_id);
            }
            if(request()->input('phone')!=null){
                $phone = (int)request()->input('phone');
                $students = $students->where('phone', $phone);
            }
        }

        $students = $students
            ->with('user')
            ->with('studenttags.tag')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->with('consultant')
            ->with('supporter')
            ->orderBy('created_at', 'desc')
            ->get();

        $moralTags = Tag::where('is_deleted', false)->where('type', 'moral')->get();
        $needTags = Tag::where('is_deleted', false)->where('type', 'need')->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();

        return view('students.index',[
            'students' => $students,
            'supports' => $supports,
            'sources' => $sources,
            'supporters_id' => $supporters_id,
            'name' => $name,
            'sources_id' => $sources_id,
            'phone' => $phone,
            'moralTags'=>$moralTags,
            'needTags'=>$needTags,
            'hotTemperatures'=>$hotTemperatures,
            'coldTemperatures'=>$coldTemperatures,
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

    //---------------------AJAX-----------------------------------
    public function tag(Request $request){
        $students_id = $request->input('students_id');
        $selectedTags = $request->input('selectedTags');

        $student = Student::where('id', $students_id)->where('is_deleted', false)->first();
        if($student==null){
            return [
                "error"=>"student_not_found",
                "data"=>null
            ];
        }

        StudentTag::where("students_id", $students_id)->update([
            "is_deleted"=>true
        ]);

        if($selectedTags){
            foreach($selectedTags as $theselectedTag) {
                $studentTag = new StudentTag;
                $studentTag->students_id = $students_id;
                $studentTag->tags_id = $theselectedTag;
                $studentTag->users_id = Auth::user()->id;
                $studentTag->save();
            }
        }


        return [
            "error"=>null,
            "data"=>null
        ];
    }

    public function temperature(Request $request){
        $students_id = $request->input('students_id');
        $selectedTemperatures = $request->input('selectedTemperatures');

        $student = Student::where('id', $students_id)->where('is_deleted', false)->first();
        if($student==null){
            return [
                "error"=>"student_not_found",
                "data"=>null
            ];
        }

        StudentTemperature::where("students_id", $students_id)->update([
            "is_deleted"=>true
        ]);

        if($selectedTemperatures){
            foreach($selectedTemperatures as $theselectedTemperature) {
                $studentTemperature = new StudentTemperature;
                $studentTemperature->students_id = $students_id;
                $studentTemperature->temperatures_id = $theselectedTemperature;
                $studentTemperature->users_id = Auth::user()->id;
                $studentTemperature->save();
            }
        }


        return [
            "error"=>null,
            "data"=>null
        ];
    }

    //---------------------API------------------------------------
    public function apiAddStudents(Request $request){
        $students = $request->input('students', []);
        $ids = [];
        $fails = [];
        foreach($students as $student){
            if(!isset($student['phone']) || !isset($student['last_name'])){
                $fails[] = $student;
                continue;
            }
            $studentObject = new Student;
            foreach($student as $key=>$value){
                $studentObject->$key = $value;
            }
            try{
                $studentObject->save();
                $ids[] = $studentObject->id;
            }catch(Exception $e){
                $fails[] = $student;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }
}
