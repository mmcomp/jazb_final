<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\NeedTagParentFour;

class NeedParentTagFourController extends Controller
{
    public function index(){
        $needParentTagFours = NeedTagParentFour::where('is_deleted', false)->orderBy('name')->get();

        return view('need_parent_tag_fours.index',[
            'needParentTagFours' => $needParentTagFours,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $needParentTagFour = new NeedTagParentFour();
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_fours.create', [
                "needParentTagFour"=>$needParentTagFour
            ]);
        }

        $needParentTagFour->name = $request->input('name', '');
        $needParentTagFour->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 3 با موفقیت افزوده شد.");
        return redirect()->route('need_parent_tag_fours');
    }

    public function edit(Request $request, $id)
    {
        $needParentTagFour = NeedTagParentFour::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagFour==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 3 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_fours');
        }
        if($request->getMethod()=='GET'){
            return view('need_parent_tag_fours.create', [
                "needParentTagFour"=>$needParentTagFour
            ]);
        }

        $needParentTagFour->name = $request->input('name', '');
        $needParentTagFour->save();

        $request->session()->flash("msg_success", "برچسب نیازسنجی فرعی 3  با موفقیت ویرایش شد.");
        return redirect()->route('need_parent_tag_fours');
    }

    public function delete(Request $request, $id)
    {
        $needParentTagFour = NeedTagParentFour::where('id', $id)->where('is_deleted', false)->first();
        if($needParentTagFour==null){
            $request->session()->flash("msg_error", "برچسب نیازسنجی فرعی 3 مورد نظر پیدا نشد!");
            return redirect()->route('need_parent_tag_fours');
        }

        $needParentTagFour->is_deleted = true;
        $needParentTagFour->save();

        $request->session()->flash("msg_success", "برچسب  نیازسنجی فرعی 3 با موفقیت حذف شد.");
        return redirect()->route('need_parent_tag_fours');
    }
}
