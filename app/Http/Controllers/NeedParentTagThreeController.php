<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\NeedTagParentThree;

class NeedParentTagThreeController extends Controller
{
    public function index(){
        $needParentTagThrees = NeedTagParentThree::where('is_deleted', false)->orderBy('name')->get();

        return view('need_parent_tag_threes.index',[
            'needParentTagThrees' => $needParentTagThrees,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $needParentTagThree = new NeedTagParentThree();
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_threes.create', [
                "needParentTagThree"=>$needParentTagThree
            ]);
        }

        $needParentTagThree->name = $request->input('name', '');
        $needParentTagThree->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 2 با موفقیت افزوده شد.");
        return redirect()->route('need_parent_tag_threes');
    }

    public function edit(Request $request, $id)
    {
        $needParentTagThree = NeedTagParentThree::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagThree==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 2 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_threes');
        }
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_threes.create', [
                "needParentTagThree"=>$needParentTagThree
            ]);
        }

        $needParentTagThree->name = $request->input('name', '');
        $needParentTagThree->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 2  با موفقیت ویرایش شد.");
        return redirect()->route('need_parent_tag_threes');
    }

    public function delete(Request $request, $id)
    {
        $needParentTagThree = NeedTagParentThree::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagThree==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 2 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_threes');
        }

        $needParentTagThree->is_deleted = true;
        $needParentTagThree->save();

        $request->session()->flash("msg_success", "برچسب  نیازسنجی فرعی 2 با موفقیت حذف شد.");
        return redirect()->route('need_parent_tag_threes');
    }
}
