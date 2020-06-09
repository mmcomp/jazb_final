<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sms_validation;
use Illuminate\Support\MessageBag;

class RegisterController extends Controller
{
    public function index(){
        if(Auth::check()){
            return view('layouts.register', [
                "error" => "قبلا ثبت نام کرده اید"
            ]);
        }
        return view('layouts.register', []);
    }
    public function sendsms(Request $request){
        if($request->getMethod()=='GET'){
            return view('layouts.register', [
                "error" => "اطلاعات صحیح ارسال نشده است"
            ]);
        }
        $request->validate([
            'mobile' => 'required|unique:posts|max:12'
        ]);
        $sms = new Sms_validation;
        $sms->mobile = $request->input('mobile');
        $sms->sms_code = rand(1000,9999) ;
        $user_info = [
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'ostan' => $request->input('ostan'),
            'city' => $request->input('city')
        ];
        $sms->user_info = json_encode($user_info,JSON_UNESCAPED_UNICODE);
        $sms->save();
        $smsMessage = "لطفا کد پیامک شده را وارد نمایید";
        return view('layouts.register', ['smsMessage'=>$smsMessage]);
    }
}
