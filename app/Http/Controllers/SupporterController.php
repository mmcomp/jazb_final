<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Group;
use App\Student;
use App\User;
use App\Source;
use App\Product;
use App\Tag;
use App\Temperature;
use App\Call;

class SupporterController extends Controller
{
    public function index(){
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->with('students.purchases')->with('students.studenttags.tag')->orderBy('max_student', 'desc')->get();
        // dd($supporters);
        return view('supporters.index',[
            'supporters' => $supporters,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function student(){
        $students = Student::where('is_deleted', false)->where('supporters_id', Auth::user()->id);
        $sources = Source::where('is_deleted', false)->get();
        $products = Product::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        if(request()->getMethod()=='POST'){
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
            ->with('calls.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $moralTags = Tag::where('is_deleted', false)->where('type', 'moral')->get();
        $needTags = Tag::where('is_deleted', false)->where('type', 'need')->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();

        return view('supporters.student',[
            'students' => $students,
            'sources' => $sources,
            'name' => $name,
            'sources_id' => $sources_id,
            'phone' => $phone,
            'moralTags'=>$moralTags,
            'needTags'=>$needTags,
            'hotTemperatures'=>$hotTemperatures,
            'coldTemperatures'=>$coldTemperatures,
            'products'=>$products,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
        //---------------------AJAX-----------------------------------
        public function call(Request $request){
            $students_id = $request->input('students_id');

            $student = Student::where('id', $students_id)->where('is_deleted', false)->first();
            if($student==null){
                return [
                    "error"=>"student_not_found",
                    "data"=>null
                ];
            }

            $call = new Call;
            $call->title = 'تماس';
            $call->students_id = $students_id;
            $call->users_id = Auth::user()->id;
            $call->description = $request->input('description');
            $call->result = $request->input('result');
            $call->replier = $request->input('replier');
            $call->products_id = $request->input('products_id');
            $call->next_to_call = $request->input('next_to_call');
            $call->next_call = $request->input('next_call');
            $call->save();

            return [
                "error"=>null,
                "data"=>null
            ];
        }
}
