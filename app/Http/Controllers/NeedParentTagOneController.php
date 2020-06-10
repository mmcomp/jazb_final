<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\NeedTagParentOne;

class NeedParentTagOneController extends Controller
{
    public function index(){
        $needParentTagOnes = NeedTagParentOne::where('is_deleted', false)->orderBy('name')->get();

        return view('need_parent_tag_ones.index',[
            'needParentTagOnes' => $needParentTagOnes,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $needParentTagOne = new NeedTagParentOne();
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_ones.create', [
                "needParentTagOne"=>$needParentTagOne
            ]);
        }

        $needParentTagOne->name = $request->input('name', '');
        $needParentTagOne->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی اصلی با موفقیت افزوده شد.");
        return redirect()->route('need_parent_tag_ones');
    }

    public function edit(Request $request, $id)
    {
        $needParentTagOne = NeedTagParentOne::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagOne==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی اصلی مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_ones');
        }
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_ones.create', [
                "needParentTagOne"=>$needParentTagOne
            ]);
        }

        $needParentTagOne->name = $request->input('name', '');
        $needParentTagOne->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی اصلی  با موفقیت ویرایش شد.");
        return redirect()->route('need_parent_tag_ones');
    }

    public function delete(Request $request, $id)
    {
        $needParentTagOne = NeedTagParentOne::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagOne==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی اصلی مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_ones');
        }

        $needParentTagOne->is_deleted = true;
        $needParentTagOne->save();

        $request->session()->flash("msg_success", "برچسب  نیازسنجی اصلی با موفقیت حذف شد.");
        return redirect()->route('need_parent_tag_ones');
    }
}
