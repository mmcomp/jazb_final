<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notice;

use Exception;

class NoticeController extends Controller
{
    public function index(){
        $notices = Notice::where('is_deleted', false)->orderBy('name')->get();

        return view('notices.index',[
            'notices' => $notices,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $notice = new Notice;
        if($request->getMethod()=='GET'){
            return view('notices.create', [
                "notice"=>$notice
            ]);
        }

        $notice->name = $request->input('name');
        $notice->save();

        $request->session()->flash("msg_success", "اطلاع رسانی با موفقیت افزوده شد.");
        return redirect()->route('notices');
    }

    public function edit(Request $request, $id)
    {
        $notice = Notice::where('id', $id)->where('is_deleted', false)->first();
        if($notice==null){
            $request->session()->flash("msg_error", "اطلاع رسانی مورد نظر پیدا نشد!");
            return redirect()->route('notices');
        }

        if($request->getMethod()=='GET'){
            return view('notices.create', [
                "notice"=>$notice
            ]);
        }

        $notice->name = $request->input('name');
        $notice->save();

        $request->session()->flash("msg_success", "اطلاع رسانی با موفقیت ویرایش شد.");
        return redirect()->route('notices');
    }

    public function delete(Request $request, $id)
    {
        $notice = Notice::where('id', $id)->where('is_deleted', false)->first();
        if($notice==null){
            $request->session()->flash("msg_error", "اطلاع رسانی مورد نظر پیدا نشد!");
            return redirect()->route('notices');
        }

        $notice->is_deleted = true;
        $notice->save();

        $request->session()->flash("msg_success", "اطلاع رسانی با موفقیت حذف شد.");
        return redirect()->route('notices');
    }

}
