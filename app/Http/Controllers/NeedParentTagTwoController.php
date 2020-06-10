<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\NeedTagParentTwo;

class NeedParentTagTwoController extends Controller
{
    public function index(){
        $needParentTagTwos = NeedTagParentTwo::where('is_deleted', false)->orderBy('name')->get();

        return view('need_parent_tag_twos.index',[
            'needParentTagTwos' => $needParentTagTwos,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $needParentTagTwo = new NeedTagParentTwo();
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_twos.create', [
                "needParentTagTwo"=>$needParentTagTwo
            ]);
        }

        $needParentTagTwo->name = $request->input('name', '');
        $needParentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 1 با موفقیت افزوده شد.");
        return redirect()->route('need_parent_tag_twos');
    }

    public function edit(Request $request, $id)
    {
        $needParentTagTwo = NeedTagParentTwo::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagTwo==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 1 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_twos');
        }
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_twos.create', [
                "needParentTagTwo"=>$needParentTagTwo
            ]);
        }

        $needParentTagTwo->name = $request->input('name', '');
        $needParentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 1  با موفقیت ویرایش شد.");
        return redirect()->route('need_parent_tag_twos');
    }

    public function delete(Request $request, $id)
    {
        $needParentTagTwo = NeedTagParentTwo::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagTwo==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 1 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_twos');
        }

        $needParentTagTwo->is_deleted = true;
        $needParentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب  نیازسنجی فرعی 1 با موفقیت حذف شد.");
        return redirect()->route('need_parent_tag_twos');
    }
}
