<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Student;
use App\Group;
use App\User;
use App\Call;
use App\CallResult;
use Carbon\Carbon;
use Morilog\Jalali\CalendarUtils;
use App\Http\Traits\AllTypeCallsTrait;

class DashboardController extends Controller
{
    use AllTypeCallsTrait;

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
            $count_all_missed_calls = $this->missed_calls()['count'];
            $count_yesterday_missed_calls = $this->yesterday_missed_calls()['count'];
            $count_of_no_need_calls = $this->no_need_calls_students()['count'];
            $recalls = Call::where('calls_id', '!=', null)->pluck('calls_id');
            $calls = Call::where('next_call', '!=', null)->where('users_id', Auth::user()->id)->whereNotIn('id', $recalls);
            $calls = $calls->get();
            $arrayOfReminders = [];
            $todayCount = $calls->where('next_call', '>=', Carbon::now()->addDays(0)->toDateString().' '."00:00:00")->where('next_call', '<=', Carbon::now()->addDays(0)->toDateString().' '."23:59:59")->count();
            for($i = 1;$i < 10; $i++){
                $arrayOfReminders[$i] = $calls->where('next_call', '>=', Carbon::now()->addDays($i)->toDateString().' '."00:00:00")->where('next_call', '<=', Carbon::now()->addDays($i)->toDateString().' '."23:59:59")->count();
            }
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
            $yesterday_recalls = Call::where('users_id', Auth::user()->id)->where('next_call', ">=", date("Y-m-d 00:00:00", strtotime("yesterday")))->where('next_call', "<=", date("Y-m-d 23:59:59", strtotime("yesterday")))->get();
            return view('dashboard.support', [
                'newStudents'=>$newStudents,
                'results'=>$results,
                'todayCount' => $todayCount,
                'arrOfReminders' => $arrayOfReminders,
                'count_no_need_calls_students' => $count_of_no_need_calls,
                'count_of_all_missed_calls' =>  $count_all_missed_calls,
                'count_of_yesterday_missed_calls' => $count_yesterday_missed_calls,
                'yesterday_recalls'=>$yesterday_recalls
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
        $supportGroupId = Group::getSupport();
        $supporters = [];
        if($supportGroupId){
            $supportGroupId = $supportGroupId->id;
            $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->with('students')->get()->sortBy(function($hackathon)
            {
                return $hackathon->students->count();
            }, SORT_REGULAR, true);
        }
        return view('dashboard.admin', [
            'devideStudents'=>$devideStudents,
            'todayStudents'=>$todayStudents,
            'results'=>$results,
            'supporters'=>$supporters
        ]);
    }
}
