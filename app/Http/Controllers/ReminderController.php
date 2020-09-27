<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Call;

use Exception;

class ReminderController extends Controller
{
    public function index(){
        $calls = Call::where('next_call', '!=', null)->where('users_id', Auth::user()->id)->with('student')->orderBy('next_call', 'desc')->get();

        return view('reminders.index',[
            'calls' => $calls,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
}
