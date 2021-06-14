<?php

namespace App\Http\Controllers;

use App\MergeStudents as AppMergeStudents;
use App\Student;
use Illuminate\Support\Facades\DB;
use Exception;

use Illuminate\Http\Request;

class MergeStudentsController extends Controller
{
    /***
     * thirnary operators for index.blade.php items
     *
     */
    public function thirnaryOperators($item)
    {
        $first = $item ? $item->first_name : '';
        $second = $item ? $item->last_name : '';
        $third = $item ? ('-' . $item->phone) : '';
        return $first . ' ' . $second . ' ' . $third;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mergedStudents = AppMergeStudents::where('is_deleted', 0)->get();
        return view('merge_students.index', [
            'mergedStudents' => $mergedStudents,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
     /**
     * Search name and phone in index of mergeStudents
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPost(Request $request)
    {
 
        //$name = $request->input('name');
        //$phone = $request->input('phone');
        // if ($request->input('name') != null) {
        //     $name = trim($request->input('name'));
        //     $student_ids = Student::where('is_deleted', false)
        //     ->where('banned', false)
        //     ->where('archived', false)
        //     ->where(DB::raw("CONCAT(IFNULL(first_name, ''), IFNULL(CONCAT(' ', last_name), ''))"), 'like', '%' . $name . '%')
        //     ->pluck('id');
        //     $purchases = $purchases->whereIn('purchases.students_id', $student_ids);
        // }
        $mergedStudents = AppMergeStudents::where('is_deleted', false);
        if ($request->input('phone') != null) {
            $phone = $request->input('phone');
            // $student_ids = Student::where('phone', 'like', '%' . $phone . '%')
            //     ->where('is_deleted', false)
            //     ->where('banned', false)
            //     ->where('archived', false)
            //     ->pluck('id');
            DB::enableQueryLog();
            $mergedStudents = $mergedStudents->with(['mainStudent' => function ($query) {
                $query->where('phone','like','721');
            }])->get()->toArray();
            dd(DB::getQueryLog());
            //dd($mergedStudents);
        }
        //end filter
        $req = $request->all();
        $allMergedStudents = $mergedStudents->get();
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc

        if ($columnName != 'row' && $columnName != "end") {
            $mergedStudents = $mergedStudents->orderBy($columnName, $columnSortOrder)
                //->select('purchases.*')
                ->skip($req['start'])
                ->take($req['length'])
                ->get();
        } else if($columnName == "main_students_id"){
            $mergedStudents = $mergedStudents
            ->orderBy('main_students_id', $columnSortOrder)
            ->skip($req['start'])
            ->take($req['length'])
            ->get();
        } else if($columnName == "auxilary_students_id") {
            $mergedStudents = $mergedStudents
            ->orderBy('auxilary_students_id', $columnSortOrder)
            ->skip($req['start'])
            ->take($req['length'])
            ->get();
        } else if($columnName == "second_auxilary_students_id") {
            $mergedStudents = $mergedStudents
            ->orderBy('second_auxilary_students_id', $columnSortOrder)
            ->skip($req['start'])
            ->take($req['length'])
            ->get();
        } else if($columnName == "third_auxilary_students_id") {
            $mergedStudents = $mergedStudents
            ->orderBy('third_auxilary_students_id', $columnSortOrder)
            ->skip($req['start'])
            ->take($req['length'])
            ->get();
        }
        $data = [];
        if ($mergedStudents) {
            foreach ($mergedStudents as $index => $item) {
                
                $btn = '<a class="btn btn-primary" href="'. route('merge_students_edit', $item->id) .'"> ویرایش</a>
                        <a class="btn btn-danger" href="'. route('merge_students_delete', $item->id).'"> حذف </a>';
                $main_part_1 =  ($item->mainStudent) ? $item->mainStudent->first_name : '-' ;
                $main_part_2 = ($item->mainStudent) ? $item->mainStudent->last_name : '-';
                $main_part_3 = ($item->mainStudent) ? $item->mainStudent->phone : '-'; 
                $auxilary_part_1 = ($item->auxilaryStudent) ? $item->auxilaryStudent->first_name : '-';
                $auxilary_part_2 = ($item->auxilaryStudent) ? $item->auxilaryStudent->last_name : '-';
                $auxilary_part_3 = ($item->auxilaryStudent) ? $item->auxilaryStudent->phone : '-';    
                $second_auxilary_part_1 = ($item->secondAuxilaryStudent) ? $item->secondAuxilaryStudent->first_name : '-';
                $second_auxilary_part_2 = ($item->secondAuxilaryStudent) ? $item->secondAuxilaryStudent->last_name : '-';
                $second_auxilary_part_3 = ($item->secondAuxilaryStudent) ? $item->secondAuxilaryStudent->phone : '-';  
                $third_auxilary_part_1 = ($item->thirdAuxilaryStudent) ? $item->thirdAuxilaryStudent->first_name : '-';
                $third_auxilary_part_2 = ($item->thirdAuxilaryStudent) ? $item->thirdAuxilaryStudent->last_name : '-';
                $third_auxilary_part_3 = ($item->thirdAuxilaryStudent) ? $item->thirdAuxilaryStudent->phone : '-';  

                $data[] = [
                    "row" => $req['start'] + $index + 1,
                    "id" => $item->id,
                    "main_students_id" => $main_part_1." ".$main_part_2."-".$main_part_3,
                    "auxilary_students_id" => $auxilary_part_1." ".$auxilary_part_2."-".$auxilary_part_3,
                    "second_auxilary_students_id" => $second_auxilary_part_1." ".$second_auxilary_part_2."-".$second_auxilary_part_3,
                    "third_auxilary_students_id" => $third_auxilary_part_1." ".$third_auxilary_part_2."-".$third_auxilary_part_3,
                    "end" => $btn
                ];
            }
        }
        $result = [
            "draw" => $req['draw'],
            "data" => $data,
            "request" => $request->all(),
            "recordsTotal" => count($allMergedStudents),
            "recordsFiltered" => count($allMergedStudents),
        ];

        return $result;

    }

    /**
     * handle error
     *
     */
    public function handleError($arr, $request, $allRequests)
    {
        $sw = 1;
        if (count($arr) != count(array_unique($arr))) {
            $sw = 0;
            $request->session()->flash("msg_error", "حداقل ۲ مورد شبیه هم انتخاب شده اند.");
        } else if (!$allRequests[0]) {
            $sw = 0;
            $request->session()->flash("msg_error", "حتما باید دانش آموز اصلی انتخاب شود");
        } else if ((!$allRequests[0] && !$allRequests[1] && !$allRequests[2] && !$allRequests[3]) || (!$allRequests[1] && !$allRequests[2] && !$allRequests[3])) {
            $sw = 0;
            $request->session()->flash("msg_error", "فیلدها خالی است.");
        }
        return $sw;
    }
    /**
     * find a repeated row in table middle_table_for_merged_students
     *
     * @return \Illuminate\Http\Response
     */
    public function findRepeatedRow($item)
    {
        $mergeMain = AppMergeStudents::where('is_deleted', false)->where(function ($query) use ($item) {
            $query->where('main_students_id', $item)->orWhere('auxilary_students_id', $item)->orWhere('second_auxilary_students_id', $item)
                ->orWhere('third_auxilary_students_id', $item)->first();
        })->first();
        return $mergeMain;
    }
    /**
     * change supporter
     *
     * @return \Illuminate\Http\Response
     */
    public function changeSupporter($item, $main, $request, $err, $id)
    {
        if ($item != null) {
            if ($item->supporters_id != $main->supporters_id) {
                $stu = Student::where('is_deleted', false)->where('id', $id)->first();
                if(isset($stu)){
                $stu->supporters_id = $main->supporters_id;
                try {
                    $stu->save();
                } catch (Exception $error) {
                    $request->session()->flash("msg_error", $err);
                    return redirect()->route('merge_students_index');
                }
            }
            }
        }
    }
    /**
     * return 2 array
     *

     */
    public function arrForComparingRepeatedItems($first, $second, $third, $forth)
    {
        $arr1 = [$first, $second, $third, $forth];
        $arr2 = array_filter($arr1);
        return $arr2;
    }
    public function makeBannedAndArchivedToBefalse($allRequests){
        $mainStudent = Student::where('id',$allRequests[0])->first();
        $auxilaryStudent = Student::where('id',$allRequests[1])->first();
        $secondAuxilaryStudent = Student::where('id',$allRequests[2])->first();
        $thirdAuxilaryStudent = Student::where('id',$allRequests[3])->first();
        if($mainStudent->archived)$mainStudent->archived = 0;
        if($mainStudent->banned)$mainStudent->banned = 0;
        if($auxilaryStudent){
            if($auxilaryStudent->archived)$auxilaryStudent->archived = 0;
            if($auxilaryStudent->banned)$auxilaryStudent->banned = 0;
            $auxilaryStudent->save();
        }
        if($secondAuxilaryStudent){
            if($secondAuxilaryStudent->archived)$secondAuxilaryStudent->archived = 0;
            if($secondAuxilaryStudent->banned)$secondAuxilaryStudent->banned = 0;
            $secondAuxilaryStudent->save();
        }
        if($thirdAuxilaryStudent){
            if($thirdAuxilaryStudent->archived)$thirdAuxilaryStudent->archived = 0;
            if($thirdAuxilaryStudent->banned)$thirdAuxilaryStudent->banned = 0;
            $thirdAuxilaryStudent->save();
        }
        $mainStudent->save();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->get();

        if ($request->getMethod() == 'GET') {
            return view('merge_students.create', [
                'students' => $students,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')

            ]);
        }
        $merged = new AppMergeStudents();
        $allRequests = [(int)$request->main, (int)$request->auxilary, (int)$request->second_auxilary, (int)$request->third_auxilary];
        $merged->main_students_id = $allRequests[0];
        $merged->auxilary_students_id = $allRequests[1];
        $merged->second_auxilary_students_id = $allRequests[2];
        $merged->third_auxilary_students_id = $allRequests[3];
        $arr_without_zeros = $this->arrForComparingRepeatedItems($allRequests[0], $allRequests[1], $allRequests[2], $allRequests[3]);
        try {
            $sw = $this->handleError($arr_without_zeros, $request, $allRequests);
            if ($sw) {
                $allRequests[0] ? $mergeMain = $this->findRepeatedRow($allRequests[0]) : $mergeMain = 0;
                $allRequests[1] ? $mergeAuxilary = $this->findRepeatedRow($allRequests[1]) : $mergeSecondAuxilary = 0;
                $allRequests[2] ? $mergeSecondAuxilary = $this->findRepeatedRow($allRequests[2]) : $mergeSecondAuxilary = 0;
                $allRequests[3] ? $mergeThirdAuxilary = $this->findRepeatedRow($allRequests[3]) : $mergeThirdAuxilary = 0;
                if (!$mergeMain && !$mergeAuxilary && !$mergeSecondAuxilary && !$mergeThirdAuxilary) {
                    try {
                        $this->makeBannedAndArchivedToBefalse($allRequests);
                        $merged->save();
                    } catch (Exception $error) {
                        $request->session()->flash("msg_error", "سطر با موفقیت افزوده نشد!");
                        return redirect()->route('merge_students_index');
                    }
                } else {
                    $request->session()->flash("msg_error", "سطر تکراری است!");
                    return redirect()->route('merge_students_index');
                }
                $this->changeSupporter($merged->auxilaryStudent, $merged->mainStudent, $request, 'تغییر پشتیبان فرعی ۱ با مشکل روبرو شد.', $allRequests[1]);
                $this->changeSupporter($merged->secondAuxilaryStudent, $merged->mainStudent, $request, 'تغییر پشتیبان فرعی ۲ با مشکل روبرو شد.', $allRequests[2]);
                $this->changeSupporter($merged->thirdAuxilaryStudent, $merged->mainStudent, $request, 'تغییر پشتیبان فرعی ۳ با مشکل روبرو شد.', $allRequests[3]);
            }
        } catch (Exception $error) {
            $request->session()->flash("msg_error", "سطر با موفقیت افزوده نشد.");
            return redirect()->route('merge_students_index');
        }
        if ($sw) {
            $request->session()->flash("msg_success", "سطر با موفقیت افزوده شد.");
            return redirect()->route('merge_students_index');
        }
        return redirect()->route('merge_students_index');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $rel = AppMergeStudents::where('id', $id)->where('is_deleted', false)->first();
        if ($rel == null) {
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد!");
            return redirect()->route('merge_students_index');
        }
        if ($request->getMethod() == 'GET') {
            return view('merge_students.create', [
                "rel" => $rel,
            ]);
        }
        $allRequests = [(int)$request->main, (int)$request->auxilary, (int)$request->second_auxilary, (int)$request->third_auxilary];
        $rel->main_students_id = $allRequests[0];
        $rel->auxilary_students_id = $allRequests[1];
        $rel->second_auxilary_students_id = $allRequests[2];
        $rel->third_auxilary_students_id = $allRequests[3];
        $arr_without_zeros = $this->arrForComparingRepeatedItems($allRequests[0], $allRequests[1], $allRequests[2], $allRequests[3]);
        try {
            $sw = $this->handleError($arr_without_zeros, $request, $allRequests);
            if ($sw) {
                $this->changeSupporter($rel->auxilaryStudent, $rel->mainStudent, $request, 'تغییر پشتیبان فرعی ۱ با مشکل روبرو شد.', $allRequests[1]);
                $this->changeSupporter($rel->secondAuxilaryStudent, $rel->mainStudent, $request, 'تغییر پشتیبان فرعی ۲ با مشکل روبرو شد.', $allRequests[2]);
                $this->changeSupporter($rel->thirdAuxilaryStudent, $rel->mainStudent, $request, 'تغییر پشتیبان فرعی ۳ با مشکل روبرو شد.', $allRequests[3]);
                $this->makeBannedAndArchivedToBefalse($allRequests);
                $rel->save();
            }
        } catch (Exception $error) {
            dd($error);
            $request->session()->flash("msg_error", "سطر با موفقیت ویرایش نشد.");
            return redirect()->route('merge_students_index');
        }
        if ($sw) {
            $request->session()->flash("msg_success", "سطر با موفقیت ویرایش شد.");
            return redirect()->route('merge_students_index');
        }
        return redirect()->route('merge_students_index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $student = AppMergeStudents::where('id', $id)->where('is_deleted', false)->first();
        if ($student == null) {
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد");
            return redirect()->route('merge_students_index');
        }

        $student->is_deleted = true;
        $student->save();

        $request->session()->flash("msg_success", "ارتباط  با موفقیت حذف شد.");
        return redirect()->route('merge_students_index');
    }
    /**
     * get students using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getStudents(Request $request)
    {

        $search = trim($request->search);
        if ($search == '') {
            $students = Student::orderby('id', 'desc')->select('id', 'first_name', 'last_name', 'phone')->where(
                'is_deleted',
                false
            )->get();
        } else {
            $students = Student::select('id', 'first_name', 'last_name', 'phone',DB::raw("CONCAT(first_name,' ',last_name)"))->where(
                'is_deleted',
                false
            )->where(function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"),'like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%');
            })->orderby('id','desc')->get();
        }
        $response = array();
        foreach ($students as $student) {
            $response[] = array(
                "id" => $student->id,
                "text" => $student->first_name . ' ' . $student->last_name . '-' . $student->phone
            );
        }
        $response[] = [
            "id" => 0,
            "text" => "-"
        ];
        return $response;
    }
}
