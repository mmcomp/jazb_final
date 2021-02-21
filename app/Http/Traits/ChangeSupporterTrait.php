<?php
namespace App\Http\Traits;
use App\Student;
use Exception;

trait ChangeSupporterTrait {
    public function returnStu($id)
    {
        return Student::where('archived', false)->where('banned', false)->where('is_deleted', false)->where('id', $id)->first();
    }
    public function giveStudentThatItsSupporterChanged($student, $supporters_id)
    {
        $student->supporters_id = $supporters_id;
        $student->supporter_seen = false;
        $student->supporter_start_date = date("Y-m-d H:i:s");
        $student->other_purchases += $student->own_purchases;
        $student->own_purchases = 0;
        $student->today_purchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->count();
        try {
            $student->save();
        } catch (Exception $e) {
            dd($e);
        }
    }
}
