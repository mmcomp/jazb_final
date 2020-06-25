<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\School;

use Exception;

class SchoolController extends Controller
{
    public function index(){
        $schools = School::where('is_deleted', false)->orderBy('name')->get();

        return view('schools.index',[
            'schools' => $schools,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $school = new School;
        if($request->getMethod()=='GET'){
            return view('schools.create', [
                "school"=>$school
            ]);
        }

        $school->name = $request->input('name', '');
        $school->save();

        $request->session()->flash("msg_success", "مدرسه با موفقیت افزوده شد.");
        return redirect()->route('schools');
    }

    public function edit(Request $request, $id)
    {
        $school = School::where('id', $id)->where('is_deleted', false)->first();
        if($school==null){
            $request->session()->flash("msg_error", "مدرسه مورد نظر پیدا نشد!");
            return redirect()->route('products');
        }

        if($request->getMethod()=='GET'){
            return view('schools.create', [
                "school"=>$school
            ]);
        }

        $school->name = $request->input('name', '');
        $school->save();

        $request->session()->flash("msg_success", "مدرسه با موفقیت ویرایش شد.");
        return redirect()->route('schools');
    }

    public function delete(Request $request, $id)
    {
        $school = School::where('id', $id)->where('is_deleted', false)->first();
        if($school==null){
            $request->session()->flash("msg_error", "مدرسه مورد نظر پیدا نشد!");
            return redirect()->route('schools');
        }

        $school->is_deleted = true;
        $school->save();

        $request->session()->flash("msg_success", "مدرسه با موفقیت حذف شد.");
        return redirect()->route('schools');
    }
}
