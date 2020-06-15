<?php

namespace App\Http\Controllers;

use App\Marketer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketerController extends Controller
{
    public function profile(){
        $user = Auth::user();
        $marketer = Marketer::where('users_id',$user->id)->first();
        //$marketer->birthdate = isset($marketer->birthdate) ? jdate("Y-m-d",strtotime($marketer->birthdate)):'';
        return view('marketers.index',['marketer'=>$marketer , 'user'=>$user]);
    }
}
