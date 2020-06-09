<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TagParentFour;

class ParentTagFourController extends Controller
{
    public function index(){
        $parentTagFours = TagParentFour::where('is_deleted', false)->orderBy('name')->get();

        return view('parent_tag_fours.index',[
            'parentTagFours' => $parentTagFours,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $parentTagFour = new TagParentFour();
        if($request->getMethod()=='GET'){
            return view('parent_tag_fours.create', [
                "parentTagFour"=>$parentTagFour
            ]);
        }

        $parentTagFour->name = $request->input('name', '');
        $parentTagFour->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 3 با موفقیت افزوده شد.");
        return redirect()->route('parent_tag_fours');
    }

    public function edit(Request $request, $id)
    {
        $parentTagFour = TagParentFour::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagFour==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 3 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_fours');
        }
        if($request->getMethod()=='GET'){
            return view('parent_tag_fours.create', [
                "parentTagFour"=>$parentTagFour
            ]);
        }

        $parentTagFour->name = $request->input('name', '');
        $parentTagFour->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 3  با موفقیت ویرایش شد.");
        return redirect()->route('parent_tag_fours');
    }

    public function delete(Request $request, $id)
    {
        $parentTagFour = TagParentFour::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagFour==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 3 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_fours');
        }

        $parentTagFour->is_deleted = true;
        $parentTagFour->save();

        $request->session()->flash("msg_success", "برچسب  اخلاقی فرعی 3 با موفقیت حذف شد.");
        return redirect()->route('parent_tag_fours');
    }
}
