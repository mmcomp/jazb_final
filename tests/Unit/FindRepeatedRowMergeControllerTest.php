<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\MergeStudents as AppMergeStudents;
use Illuminate\Http\Request;

class FindRepeatedRowMergeControllerTest extends TestCase
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
    public function testFindRepeatedRowInMergeController()
    {
        $item = 64;
        $mergeMain = AppMergeStudents::where('is_deleted', false)->where(function ($query) use ($item) {
            $query->where('main_students_id', $item)->orWhere('auxilary_students_id', $item)->orWhere('second_auxilary_students_id', $item)
                ->orWhere('third_auxilary_students_id', $item)->first();
        })->first();
        $this->assertEquals($mergeMain, null);
        $this->assertDatabaseHas('middle_table_for_merged_students',["main_students_id" => 6504]);
    }
    public function testEditInMergeController(){
        $id = 3;
        $rel = AppMergeStudents::where('id', $id)->where('is_deleted', false)->first();
        $this->assertNotEquals($rel,null);
    }
}
