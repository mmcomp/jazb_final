<?php

namespace App\Http\Controllers;

use App\Marketer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketerController extends Controller
{
    public static function hamed_jalalitomiladi($str){
		$s=explode('/',$str);
		$out = "";
		if(count($s)==3){
			$y = (int)$s[0];
			$m = (int)$s[1];
			$d = (int)$s[2];
			if($d > $y)
			{
				$tmp = $d;
				$d = $y;
				$y = $tmp;
			}
			$y = (($y<1000)?$y+1300:$y);
			$miladi=\Morilog\Jalali\CalendarUtils::toGregorian($y,$m,$d);
			$out=$miladi[0]."-".$miladi[1]."-".$miladi[2];
		}
		return $out;
	}  
    public function profile(Request $request){
        $user = Auth::user();
        $marketer = Marketer::where('users_id', $user->id)->first();
        $msg = '';
        if ($request->getMethod() == 'POST') {
            $dateTime = self::hamed_jalalitomiladi($request->input('birthdate'));
            if($dateTime==''){
                $msg = 'تاریخ صحیح وارد نشده است';
                $request->session()->flash("msg_success", 'تاریخ صحیح وارد نشده است');
                return redirect()->route('marketerprofile');
            }
            $marketer->first_name = $request->input('first_name');
            $marketer->last_name = $request->input('last_name');
            $marketer->national_code = $request->input('national_code');
            $marketer->birthdate = $dateTime;
            $marketer->address = $request->input('address');
            $marketer->home_phone = $request->input('home_phone');
            $marketer->bank_card = $request->input('bank_card');
            $marketer->bank_shaba = $request->input('bank_shaba');
            $marketer->background = $request->input('background');
            $marketer->education = $request->input('education');
            $marketer->major = $request->input('major');
            $marketer->university = $request->input('university');
            if ($request->file('image_path')) {
                $allowMimeTypes = ['image/png','image/jpeg'];
                if(in_array($request->file('image_path')->getMimeType(),$allowMimeTypes)){
                    if (file_exists($marketer->image_path)) {
                        unlink($marketer->image_path);
                    }
                    $filename = time() . '.' . $request->file('image_path')->extension();
                    $marketer->image_path = 'uploads/' . $request->file('image_path')->storeAs('marketers', $filename, 'public_uploads');
                }
                else{
                    $msg = 'نوع فایل انتخابی مجاز نمی باشد';
                }
            }
            $marketer->save();
        }
        $marketer->birthdate = isset($marketer->birthdate) ? jdate($marketer->birthdate)->format('%Y/%m/%d') : '';
        return view('marketers.profile', ['marketer' => $marketer, 'user' => $user , 'msg'=>$msg]);
    }
    public function dashboard(){
        return view('marketers.dashboard');
    }
    
    public function students(){
        //
    }
    public function payments(){
        //
    }
    public function circulars(){
        //
    }
    public function mails(){
        //
    }
    public function products(){
        //
    }
    public function discounts(){
        //
    }
    public function code(){
        //
    }
}
