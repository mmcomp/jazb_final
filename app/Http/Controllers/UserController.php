<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request){
        Auth::logout();
        if($request->method()=='GET'){
            return view('layouts.login');
        }

        if($request->input('email')==null || $request->input('password')==null){
            return view('layouts.login', [
                "error" => new Exception(config('Constants.errors.inavlid_input'))
            ]);
        }

        try{
            if(Auth::attempt(["email"=>$request->input('email'), "password"=>$request->input('password')])){
                return redirect()->route('home');
            }else{
                return view('layouts.login', [
                    "error" => new Exception(config('Constants.errors.inavlid_email_password'))
                ]);
            }
        }catch(Exception $error){
            return view('layouts.login', [
                "error" => $error
            ]);
        }
    }
}
