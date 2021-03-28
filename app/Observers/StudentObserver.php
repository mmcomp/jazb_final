<?php

namespace App\Observers;

use App\Student;
use App\SupporterHistory;
use Exception;
use Log;

class StudentObserver
{
    /**
     * Handle the student "created" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function created(Student $student)
    {
        $supporterHistory = new SupporterHistory;
        $supporterHistory->users_id = $student->users_id;
        $supporterHistory->supporters_id = $student->supporters_id;
        $supporterHistory->students_id = $student->id;
        try {
            $supporterHistory->save();
        } catch (Exception $e) {
            Log::info("Fail in Student Observer Created " . $e);
        }
    }
    /**
     * Handle the student "updated" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function updated(Student $student)
    {
        if ($student->isDirty('supporters_id')) {
            $supporterHistory = new SupporterHistory;
            $supporterHistory->users_id = $student->users_id;
            $supporterHistory->supporters_id = $student->supporters_id;
            $supporterHistory->students_id = $student->id;
            try {
                $supporterHistory->save();
            } catch (Exception $e) {
                Log::info("Fail in Student Observer Updated " . $e);
            }
        }
    }
}
