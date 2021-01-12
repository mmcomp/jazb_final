<?php

namespace App\Http\Controllers;
use App\MergeStudents as AppMergeStudents;
use App\Student;
use Exception;

use Illuminate\Http\Request;

class MergeStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mergedStudents = AppMergeStudents::where('is_deleted',0)->get();
        return view('merge_students.index',[
          'mergedStudents' => $mergedStudents,
          'msg_success' => request()->session()->get('msg_success'),
          'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->get();

        if($request->getMethod() == 'GET'){
        return view('merge_students.create',[
        'students' => $students,
        'msg_success' => request()->session()->get('msg_success'),
        'msg_error' => request()->session()->get('msg_error')

        ]);
        }
        $merged = new AppMergeStudents();
        $main = $request->main;
        $auxilary = $request->auxilary;
        $second_auxilary = $request->second_auxilary;
        $third_auxilary = $request->third_auxilary;
        $merged->main_students_id = $main;
        $merged->auxilary_students_id = $auxilary;
        $merged->second_auxilary_students_id = $second_auxilary;
        $merged->third_auxilary_students_id = $third_auxilary;
        $arr = [$main,$auxilary,$second_auxilary,$third_auxilary];
        $arr_without_zeros = array_filter($arr);
        try{
        if(count($arr_without_zeros) != count(array_unique($arr_without_zeros))){
        $request->session()->flash("msg_error", "حداقل ۲ مورد شبیه هم انتخاب شده اند.");
        return redirect()->back();

        }else{
        $merged->save();
        }
        }catch(Exception $error)
        {
        $request->session()->flash("msg_error", "سطر با موفقیت افزوده نشد.");
        return redirect()->route('merge_students_index');
        }

        $request->session()->flash("msg_success", "سطر با موفقیت افزوده شد.");
        return redirect()->route('merge_students_index');


        }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $rel = AppMergeStudents::where('id', $id)->where('is_deleted', false)->first();
        //dd($request);
        if($rel==null){
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد!");
            return redirect()->route('merge_students_index');
        }
        if($request->getMethod()=='GET'){
            return view('merge_students.create', [
                "rel" => $rel,
            ]);
        }
        $rel->main_students_id = (int)$request->main;
        $rel->auxilary_students_id = (int)$request->auxilary;
        $rel->second_auxilary_students_id = (int)$request->second_auxilary;
        $rel->third_auxilary_students_id = (int)$request->third_auxilary;
        $rel->save();

        $request->session()->flash("msg_success", "ارتباط با موفقیت ویرایش شد.");
        return redirect()->route('merge_students_index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $student = AppMergeStudents::where('id', $id)->where('is_deleted', false)->first();
        if($student==null){
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد");
            return redirect()->route('merge_students_index');
        }

        $student->is_deleted = true;
        $student->save();

        $request->session()->flash("msg_success", "ارتباط  با موفقیت حذف شد.");
        return redirect()->route('merge_students_index');
    }
    /**
     * get students using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getStudents(Request $request){

        $search = trim($request->search);
        if($search == ''){
           $students = Student::orderby('id','desc')->select('id', 'first_name', 'last_name')->where('is_deleted',
           false)->where('banned', false)->where('archived', false)->get();
           }else{
           $students = Student::orderby('id','desc')->select('id', 'first_name', 'last_name')->where('is_deleted',
           false)->where('banned', false)->where('archived', false)->where(function($query) use ($search) {
           $query->where('first_name', 'like', '%' .$search . '%')->orWhere('last_name', 'like', '%' .$search . '%');
           })->get();
           }
           $response = array();
           foreach($students as $student){
           $response[] = array(
                "id"=>$student->id,
                "text"=>$student->first_name.' '.$student->last_name
           );
        }

        return $response;
    }
}
