<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Call;

use Exception;

class ReminderController extends Controller
{
    public function index(){
        $calls = Call::where('next_call', '!=', null)->where('users_id', Auth::user()->id)->with('student')->with('product')->orderBy('next_call', 'desc')->get();
        // foreach($calls as $index=>$call) {
        //     $calls[$index]->product->parents = "-";
        //     if($call->product->collection) {
        //         $parents = $call->product->collection->parents();
        //         $name = ($parents!='')?$parents . "->" . $call->product->collection->name : $call->product->collection->name;
        //         $calls[$index]->product->parents = $name;
        //     }
        // }
        return view('reminders.index',[
            'calls' => $calls,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
}
