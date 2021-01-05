<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CallResult;

use Exception;

class CallResultController extends Controller
{
    public function index(){
        $callResults = CallResult::where('is_deleted', false)->orderBy('title')->get();

        return view('call_results.index',[
            'callResults' => $callResults,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $callResult = new CallResult;
        if($request->getMethod()=='GET'){
            return view('call_results.create', [
                "callResult"=>$callResult
            ]);
        }

        $callResult->title = $request->input('title');
        $callResult->description = $request->input('description');
        $callResult->users_id = Auth::user()->id;
        $callResult->no_call = ($request->input('no_call')!=null)?true:false;
        $callResult->no_answer = ($request->input('no_answer')!=null)?true:false;
        $callResult->save();

        $request->session()->flash("msg_success", "نتیجه با موفقیت افزوده شد.");
        return redirect()->route('call_results');
    }

    public function edit(Request $request, $id)
    {
        $callResult = CallResult::where('id', $id)->where('is_deleted', false)->first();
        if($callResult==null){
            $request->session()->flash("msg_error", "نتیجه مورد نظر پیدا نشد!");
            return redirect()->route('call_results');
        }

        if($request->getMethod()=='GET'){
            return view('call_results.create', [
                "callResult"=>$callResult
            ]);
        }

        $callResult->title = $request->input('title');
        $callResult->description = $request->input('description');
        $callResult->users_id = Auth::user()->id;
        $callResult->no_call = ($request->input('no_call')!=null)?true:false;
        $callResult->no_answer = ($request->input('no_answer')!=null)?true:false;
        $callResult->save();

        $request->session()->flash("msg_success", "محصول با موفقیت ویرایش شد.");
        return redirect()->route('call_results');
    }

    public function delete(Request $request, $id)
    {
        $callResult = CallResult::where('id', $id)->where('is_deleted', false)->first();
        if($callResult==null){
            $request->session()->flash("msg_error", "نتیجه مورد نظر پیدا نشد!");
            return redirect()->route('call_results');
        }

        $callResult->is_deleted = true;
        $callResult->save();

        $request->session()->flash("msg_success", "نتیجه با موفقیت حذف شد.");
        return redirect()->route('call_results');
    }
}
