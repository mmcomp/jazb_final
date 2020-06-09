<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TagParentTwo;

class ParentTagTwoController extends Controller
{
    public function index(){
        $parentTagTwos = TagParentTwo::where('is_deleted', false)->orderBy('name')->get();

        return view('parent_tag_twos.index',[
            'parentTagTwos' => $parentTagTwos,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $parentTagTwo = new TagParentTwo();
        if($request->getMethod()=='GET'){
            return view('parent_tag_twos.create', [
                "parentTagTwo"=>$parentTagTwo
            ]);
        }

        $parentTagTwo->name = $request->input('name', '');
        $parentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 1 با موفقیت افزوده شد.");
        return redirect()->route('parent_tag_twos');
    }

    public function edit(Request $request, $id)
    {
        $parentTagTwo = TagParentTwo::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagTwo==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 1 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_twos');
        }
        if($request->getMethod()=='GET'){
            return view('parent_tag_twos.create', [
                "parentTagTwo"=>$parentTagTwo
            ]);
        }

        $parentTagTwo->name = $request->input('name', '');
        $parentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 1  با موفقیت ویرایش شد.");
        return redirect()->route('parent_tag_twos');
    }

    public function delete(Request $request, $id)
    {
        $parentTagTwo = TagParentTwo::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagTwo==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 1 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_twos');
        }

        $parentTagTwo->is_deleted = true;
        $parentTagTwo->save();

        $request->session()->flash("msg_success", "برچسب  اخلاقی فرعی 1 با موفقیت حذف شد.");
        return redirect()->route('parent_tag_twos');
    }
}
