<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(){
        return view('layouts.register', []);
    }
    public function sendsms($mobile){
        return view('layouts.register', ['mobile']);
    }
}
