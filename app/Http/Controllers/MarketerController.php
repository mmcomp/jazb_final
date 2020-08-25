<?php

namespace App\Http\Controllers;

use App\Marketer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Student;
use App\Group;
use App\User;
use App\Source;

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
    
    public function mystudents(){
        $students = Student::where('is_deleted', false)->where('marketers_id',Auth::user()->id);
        $students = $students
            ->with('user')
            ->with('studentcollections.collection')
            ->with('studenttemperatures.temperature')
            ->with('consultant')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('marketers.mystudents', ['students' => $students]);
    }

    public function createStudents(Request $request){
        $student = new Student();
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $consultantGroupId = Group::getConsultant();
        if($consultantGroupId)
            $consultantGroupId = $consultantGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $consultants = User::where('is_deleted', false)->where('groups_id', $consultantGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        if($request->getMethod()=='GET'){
            return view('marketers.create', [
                "supports"=>$supports,
                "consultants"=>$consultants,
                "sources"=>$sources,
                "student"=>$student
            ]);
        }
        $tmp = Student::where('phone', $request->input('phone'))->select('id')->first();
        $student->users_id = Auth::user()->id;
        $student->marketers_id = $student->users_id ;
        $student->first_name = $request->input('first_name');
        $student->last_name = $request->input('last_name');
        $student->last_year_grade = (int)$request->input('last_year_grade');
        $student->consultants_id = 0 ;
        $student->parents_job_title = $request->input('parents_job_title');
        $student->home_phone = $request->input('home_phone');
        $student->egucation_level = $request->input('egucation_level');
        $student->father_phone = $request->input('father_phone');
        $student->mother_phone = $request->input('mother_phone');
        $student->phone  = $request->input('phone');
        $student->school = $request->input('school');
        $student->average = $request->input('average');
        $student->major = $request->input('major');
        $student->student_phone = $request->input('student_phone');
        if(isset($tmp) && $tmp->id > 0){
            $student->id = -1;
            return view('marketers.create', [
                "supports"=>$supports,
                "consultants"=>$consultants,
                "sources"=>$sources,
                "student"=>$student,
                "msg" => 'دانش آموز قبلا ثبت شده است '
            ]);
        }
        $student->save();

        $request->session()->flash("msg_success", "دانش آموز با موفقیت افزوده شد.");
        \Artisan::call('createWpUser', [
            'user'        => $student->phone,
            'pass'        => $student->phone,
            'first_name'  => $student->first_name,
            'last_name'   => $student->last_name,
            'marketers_id'=> $student->marketers_id
        ]);
        return redirect()->route('marketermystudents');
    }
    public function students(){
        $students = Student::where('is_deleted', false)->where('marketers_id',Auth::user()->id);
        $students = $students
            ->with('user')
            ->with('studentcollections.collection')
            ->with('studenttemperatures.temperature')
            ->with('consultant')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('marketers.students', ['students' => $students]);
    }

    public function payments(){
        return view('marketers.payments');
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
        return view('marketers.code', ['code' => Auth::user()->id]);
    }
}
