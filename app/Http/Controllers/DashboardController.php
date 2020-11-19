<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Student;
use App\Group;
use App\User;
use Morilog\Jalali\CalendarUtils;

class DashboardController extends Controller
{
    public static function fixDateDigits($inp){
        $out = explode('-', $inp);
        if(count($out)!=3){
            return $inp;
        }
        if((int)$out[1]<10){
            $out[1] = '0' . $out[1];
        }
        if((int)$out[2]<10){
            $out[2] = '0' . $out[2];
        }
        return implode('-', $out);
    }

    public function index(){
        $user = Auth::user();
        $group = $user->group()->first();
        $gates = $group->gates()->where('key', 'marketers')->get();
        if(count($gates)>0){
            return redirect()->route('marketerdashboard');
        }
        $gates = $group->gates()->where('key', 'supporters')->get();
        if(count($gates)>0){
            $newStudents = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', Auth::user()->id)->where('supporter_seen', false)->count();
            $year = (int)jdate()->format("Y");
            $month = (int)jdate()->format("m");
            $startOfYear = implode('-', CalendarUtils::toGregorian($year, 1, 1));

            $dates = [$startOfYear];
            $currentMonth = 2;
            do{
                $dates[] = implode('-', CalendarUtils::toGregorian($year, $currentMonth, 1));
                $currentMonth++;
            }while($currentMonth <= $month);
            $dates[] = date("Y-m-d");
            $results = [];
            for($i = 0;$i < count($dates)-1;$i++){
                $results[] = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', Auth::user()->id)->where('created_at', '>=', DashboardController::fixDateDigits($dates[$i]) . ' 00:00:00')->where('created_at', '<', DashboardController::fixDateDigits($dates[$i+1]) . ' 00:00:00')->count();
            }
            return view('dashboard.support', [
                'newStudents'=>$newStudents,
                'results'=>$results
            ]);
        }

        $devideStudents = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', 0)->count();
        $todayStudents = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', 0)->where('created_at', '>=', date('Y-m-d 00:00:00'))->where('created_at', '<=', date('Y-m-d 23:59:59'))->count();
        $year = (int)jdate()->format("Y");
        $month = (int)jdate()->format("m");
        $startOfYear = implode('-', CalendarUtils::toGregorian($year, 1, 1));

        $dates = [$startOfYear];
        $currentMonth = 2;
        do{
            $dates[] = implode('-', CalendarUtils::toGregorian($year, $currentMonth, 1));
            $currentMonth++;
        }while($currentMonth <= $month);
        $dates[] = date("Y-m-d");
        $results = [];
        for($i = 0;$i < count($dates)-1;$i++){
            $results[] = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('created_at', '>=', DashboardController::fixDateDigits($dates[$i]) . ' 00:00:00')->where('created_at', '<', DashboardController::fixDateDigits($dates[$i+1]) . ' 00:00:00')->count();
        }
        // dump($dates);dd($results);
        $supportGroupId = Group::getSupport();
        $supporters = [];
        if($supportGroupId){
            $supportGroupId = $supportGroupId->id;
            $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->with('students')->get()->sortBy(function($hackathon)
            {
                return $hackathon->students->count();
            }, SORT_REGULAR, true);
        }
        // dd($supporters);
        return view('dashboard.admin', [
            'devideStudents'=>$devideStudents,
            'todayStudents'=>$todayStudents,
            'results'=>$results,
            'supporters'=>$supporters
        ]);
    }
}
