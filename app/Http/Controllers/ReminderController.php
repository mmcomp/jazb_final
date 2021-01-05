<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Call;

use Exception;

class ReminderController extends Controller
{
    public function index(){
        $recalls = Call::where('calls_id', '!=', null)->pluck('calls_id');
        $calls = Call::where('next_call', '!=', null)->where('users_id', Auth::user()->id)->whereNotIn('id', $recalls);
        $today = false;
        if(request()->getMethod()=='POST') {
            if(request()->input('today') && request()->input('today')=='true') {
                $today = true;
                $calls = $calls->where('next_call', '>=', date("Y-m-d 00:00:00"))->where('next_call', '<=', date("Y-m-d 23:59:59"));
            }
        }
        $calls = $calls->with('student')->with('product')->orderBy('next_call', 'desc')->get();
        return view('reminders.index',[
            'calls' => $calls,
            'today' => $today,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
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
