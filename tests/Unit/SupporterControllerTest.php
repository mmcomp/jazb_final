<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Student;
use App\Purchase;
use App\StudentTag;

class SupporterControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    public function testStudentFunction()
    {
        $purchases = Purchase::whereIn('products_id', [14, 15, 16])->where('supporters_id', 12)->pluck('students_id');
        $student_tags = StudentTag::whereIn('tags_id', [2, 3, 4])->where('users_id', 12)->where('is_deleted', false)->pluck('students_id');
        $need_tags = StudentTag::whereIn('tags_id', [66, 67, 68])->where('users_id', 12)->where('is_deleted', false)->pluck('students_id');
        $school = "";
        $last_year_grade = 12;
        $average = 12;
        $source = 0;
        $students =  Student::where('is_deleted', false)
            ->where('banned', false)
            ->where('archived', false)
            ->where('supporters_id', 12);
        if ($average && $last_year_grade) {
            $students = $students->whereIn('id', $purchases)
                ->whereIn('id', $student_tags)
                ->whereIn('id', $need_tags)
                ->where('last_year_grade', '<=', $last_year_grade)
                ->where('average', '>=', $average)
                ->where('sources_id', $source)
                ->where('school', $school);
        } elseif ($average) {
            $students = $students->whereIn('id', $purchases)
                ->whereIn('id', $student_tags)
                ->whereIn('id', $need_tags)
                ->where('average', '>=', $average)
                ->where('sources_id', $source)
                ->where('school', $school);
        } elseif ($last_year_grade) {
            $students = $students->whereIn('id', $purchases)
                ->whereIn('id', $student_tags)
                ->whereIn('id', $need_tags)
                ->where('last_year_grade', '<=', $last_year_grade)
                ->where('sources_id', $source)
                ->where('school', $school);
        }
        $students = $students->whereIn('id', $purchases)
            ->whereIn('id', $student_tags)
            ->whereIn('id', $need_tags)
            ->where('sources_id', $source)
            ->where('school', $school)->get();
        $this->assertJsonStringEqualsJsonString($students,'[]');
    }
}
