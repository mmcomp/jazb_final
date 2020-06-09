<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TagParentThree;

class ParentTagThreeController extends Controller
{
    public function index(){
        $parentTagThrees = TagParentThree::where('is_deleted', false)->orderBy('name')->get();

        return view('parent_tag_threes.index',[
            'parentTagThrees' => $parentTagThrees,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $parentTagThree = new TagParentThree();
        if($request->getMethod()=='GET'){
            return view('parent_tag_threes.create', [
                "parentTagThree"=>$parentTagThree
            ]);
        }

        $parentTagThree->name = $request->input('name', '');
        $parentTagThree->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 2 با موفقیت افزوده شد.");
        return redirect()->route('parent_tag_threes');
    }

    public function edit(Request $request, $id)
    {
        $parentTagThree = TagParentThree::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagThree==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 2 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_threes');
        }
        if($request->getMethod()=='GET'){
            return view('parent_tag_threes.create', [
                "parentTagThree"=>$parentTagThree
            ]);
        }

        $parentTagThree->name = $request->input('name', '');
        $parentTagThree->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی فرعی 2  با موفقیت ویرایش شد.");
        return redirect()->route('parent_tag_threes');
    }

    public function delete(Request $request, $id)
    {
        $parentTagThree = TagParentThree::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagThree==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی فرعی 2 مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_threes');
        }

        $parentTagThree->is_deleted = true;
        $parentTagThree->save();

        $request->session()->flash("msg_success", "برچسب  اخلاقی فرعی 2 با موفقیت حذف شد.");
        return redirect()->route('parent_tag_threes');
    }
}
