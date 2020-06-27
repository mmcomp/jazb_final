<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Group;
use App\Student;
use App\User;

use Exception;

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
}
