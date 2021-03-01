<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Call;

use Exception;

class ReminderController extends Controller
{
    public function index($date=null){
        $recalls = Call::where('calls_id', '!=', null)->pluck('calls_id');
        $calls = Call::where('next_call', '!=', null)->where('users_id', Auth::user()->id)->whereNotIn('id', $recalls);
        $today = false;
        $sw = null;
        $count = 0;
        $persons = [
            "student"=>"دانش آموز",
            "father"=>"پدر",
            "mother"=>"مادر",
            "other"=>"غیره"
        ];
        if($date == "today"){
            $calls = $calls->where('next_call', '>=', date("Y-m-d 00:00:00"))->where('next_call', '<=', date("Y-m-d 23:59:59"));
        }
        if(request()->getMethod()=='POST') {
            if(request()->input('today') && request()->input('today')=='true') {
                $today = true;
                $calls = $calls->where('next_call', '>=', date("Y-m-d 00:00:00"))->where('next_call', '<=', date("Y-m-d 23:59:59"));
            }
        }
        $theCalls = $calls->with('student')->with('product');
        $calls = $calls->with('student')->with('product')->orderBy('next_call', 'desc')->get();

        if(request()->getMethod() == "GET"){
            return view('reminders.index',[
                'calls' => $calls,
                'today' => $today,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else{
            $req =  request()->all();
            if (!isset($req['start'])) {
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $theCalls = $theCalls->skip($req['start'])
            ->take($req['length'])->get();
            $columnIndex_arr = $req['order'];
            $columnName_arr = $req['columns'];
            $order_arr = $req['order'];
            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc

            // if($columnName != 'row' && $columnName != 'end'){
            //     $sw = "all";
            //     $students = $students->orderBy($columnName,$columnSortOrder)
            //     ->select('students.*')
            //     ->skip($req['start'])
            //     ->take($req['length'])
            //     ->with('user')
            //     ->with('studenttags.tag.parent_four')
            //     ->with('studentcollections.collection.parent')
            //     ->with('studenttemperatures.temperature')
            //     ->with('source')
            //     ->with('consultant')
            //     ->with('supporter')
            //     ->get();
            // }else{
            //     $sw = "other";
            //     $students = $students->select('students.*')
            //     ->skip($req['start'])
            //     ->take($req['length'])
            //     ->with('user')
            //     ->with('studenttags.tag.parent_four')
            //     ->with('studentcollections.collection.parent')
            //     ->with('studenttemperatures.temperature')
            //     ->with('source')
            //     ->with('consultant')
            //     ->with('supporter')
            //     ->get();
            // }
            $data = [];
            foreach ($theCalls as $index => $item) {
                $route_of_reminder_delete = route('reminder_delete',['id' => $item->id]);
                $route_of_supporters_students = route('supporter_students');
                $data[] = [
                    "row" => $index + 1,
                    "id" => $item->id,
                    "student" => ($item->student)?$item->student->first_name . ' ' . $item->student->last_name:'-',
                    "product" => ($item->product)?(($item->product->parents!='-')?$item->product->parents . '->':'') . $item->product->name:'-',
                    "replier" => $persons[$item->replier],
                    "callresult" => ($item->callresult)?$item->callresult->title:'-',
                    "next_call" => ($item->next_call)?jdate($item->next_call)->format("Y/m/d"):'-' ,
                    "next_to_call" => ($item->next_to_call)?$persons[$item->next_to_call]:'-',
                    "description" => $item->description,
                    "end" => "<a class='btn btn-danger' href='$route_of_reminder_delete'>
                                  حذف
                             </a>
                            <form method='get' action='$route_of_supporters_students' >
                            <input type='hidden' name='students_id' value='$item->students_id' />
                            <input type='hidden' name='calls_id' value='$item->id' />
                            <button class='btn btn-primary'>
                               تماس
                            </button>
                           </form>"
                ];
            }
            // if($sw == null || $sw == "all" || $sw == "other"){
            //     $count = count($allStudents);
            // }

            $result = [
                "draw" => $req['draw'],
                "data" => $data,
                "recordsTotal" => count($calls),
                "recordsFiltered" => count($calls),
            ];

            return $result;
        }


    }

    public function delete($id)
    {
        $call = Call::find($id);
        if($call==null) {
            request()->session()->flash("msg_error", "تماس مورد نظر پیدا نشد!");
            return redirect()->route('reminders');
        }
        $call->delete();
        request()->session()->flash("msg_success", "تماس با موفقیت حذف شد.");
        return redirect()->route('reminders');
    }
}
