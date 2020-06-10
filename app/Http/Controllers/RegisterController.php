<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sms_validation;
use App\Province;
use Illuminate\Support\MessageBag;

class RegisterController extends Controller
{
    public function index(Request $request){
        if(Auth::check()){
            return view('layouts.register', [
                "error" => "قبلا ثبت نام کرده اید"
            ]);
        }
        $provinces = Province::pluck('name', 'id');
        $user_info=[];
        if($request){
            $user_info = [
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'provinces' => $request->input('provinces'),
                'city' => $request->input('city'),
                'mobile'=> $request->input('mobile')
            ];
        }
        return view('layouts.register', ['provinces'=>$provinces,'user_info'=>$user_info]);
    }
    public function sendsms(Request $request){
        if($request->getMethod()=='GET'){
            return view('layouts.register', [
                "error" => "اطلاعات صحیح ارسال نشده است"
            ]);
        }
        $request->validate([
            'mobile' => 'required|min:11|max:11',
            'fname' => 'required|max:100',
            'lname' => 'required|max:100',
            'province' => 'required',
            'city' => 'required'
        ]);
        $sms = new Sms_validation;
        $sms->mobile = $request->input('mobile');
        $sms->sms_code = rand(1000,9999) ;
        $user_info = [
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'provinces' => $request->input('provinces'),
            'city' => $request->input('city'),
            'mobile'=> $request->input('mobile')
        ];
        $sms->user_info = json_encode($user_info,JSON_UNESCAPED_UNICODE);
        Sms_validation::where('mobile',$sms->mobile)->delete();
        $sms->save();
        $smsMessage = "لطفا کد پیامک شده را وارد نمایید";
        return view('layouts.register', ['smsMessage'=>$smsMessage , 'provinces'=>[] ,'mobile'=>$sms->mobile]);
    }
    public function checksms(Request $request){
        if($request->getMethod()=='GET'){
            return view('layouts.register', [
                "error" => "اطلاعات صحیح ارسال نشده است"
            ]);
        }9+9-
        $request->validate([
            'sms_code' => 'required|min:4|max:4'
        ]);
        
        $smsMessage = "ثبت نام با موفقیت انجام شد";
        return view('layouts.register', ['smsMessage'=>$smsMessage , 'provinces'=>[] ,'mobile'=>'']);
    }
}
