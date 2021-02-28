<?php
namespace App\Http\Traits;
use App\Student;
use App\Call;
use App\CallResult;
use Illuminate\Support\Facades\Auth;
use Exception;

trait AllTypeCallsTrait {
    public function missed_calls()
    {
        $call_results = CallResult::where('no_answer', 1)->where('is_deleted', false)->get();
        $all_missed_calls = [];
        $count = 0;
        if ($call_results) {
            foreach ($call_results as $result) {
                $all_missed_calls = Call::where('users_id', Auth::user()->id)->where('call_results_id', $result->id)->get();
                $count = Call::where('users_id', Auth::user()->id)->where('call_results_id', $result->id)->count();
            }
        }
        return ['value' => $all_missed_calls, 'count' => $count];

    }
    public function yesterday_missed_calls(){
        $call_results = CallResult::where('no_answer', 1)->where('is_deleted', false)->get();
        $missed_calls_of_yesterday = [];
        $count = 0;
        if ($call_results) {
            foreach ($call_results as $result) {
                $missed_calls_of_yesterday = Call::where('users_id', Auth::user()->id)->where('call_results_id', $result->id)->where('created_at', ">=", date("Y-m-d 00:00:00", strtotime("yesterday")))->where('created_at', "<=", date("Y-m-d 23:59:59", strtotime("yesterday")))->get();
                $count = Call::where('users_id', Auth::user()->id)->where('call_results_id', $result->id)->where('created_at', ">=", date("Y-m-d 00:00:00", strtotime("yesterday")))->where('created_at', "<=", date("Y-m-d 23:59:59", strtotime("yesterday")))->count();
            }
        }
        return ['value' => $missed_calls_of_yesterday,'count' => $count ] ;
    }
    public function no_need_calls_students(){
        $call_results_no_need = CallResult::where('no_call', 1)->where('is_deleted', false)->get();
        $no_need_calls = [];
        $no_need_calls_students = [];
        $count = 0;
        $arrOfNoNeed = [];
        if ($call_results_no_need) {
            foreach ($call_results_no_need as $result) {
                $arrOfNoNeed[] = $result->id;
                $no_need_calls = Call::where('users_id', Auth::user()->id)->whereIn('call_results_id', $arrOfNoNeed)->pluck('students_id')->implode(',');
            }
        }
        if(!is_array($no_need_calls)){
            $no_need_calls = array_unique(explode(',', $no_need_calls));
            $no_need_calls_students = Student::where('is_deleted', false)->where('banned', false)->where('archived',false)->whereIn('id',$no_need_calls)->get();
            $count = Student::where('is_deleted', false)->where('banned', false)->where('archived',false)->whereIn('id',$no_need_calls)->count();
        }
        return ['value' => $no_need_calls_students, 'count' => $count];
    }

}
