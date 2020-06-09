<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TagParentOne;

class ParentTagOneController extends Controller
{
    public function index(){
        $parentTagOnes = TagParentOne::where('is_deleted', false)->orderBy('name')->get();

        return view('parent_tag_ones.index',[
            'parentTagOnes' => $parentTagOnes,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $parentTagOne = new TagParentOne();
        if($request->getMethod()=='GET'){
            return view('parent_tag_ones.create', [
                "parentTagOne"=>$parentTagOne
            ]);
        }

        $parentTagOne->name = $request->input('name', '');
        $parentTagOne->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی اصلی با موفقیت افزوده شد.");
        return redirect()->route('parent_tag_ones');
    }

    public function edit(Request $request, $id)
    {
        $parentTagOne = TagParentOne::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagOne==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی اصلی مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_ones');
        }
        if($request->getMethod()=='GET'){
            return view('parent_tag_ones.create', [
                "parentTagOne"=>$parentTagOne
            ]);
        }

        $parentTagOne->name = $request->input('name', '');
        $parentTagOne->save();

        $request->session()->flash("msg_success", "برچسب اخلاقی اصلی  با موفقیت ویرایش شد.");
        return redirect()->route('parent_tag_ones');
    }

    public function delete(Request $request, $id)
    {
        $parentTagOne = TagParentOne::where('id', $id)->where('is_deleted', false)->first();
        if($parentTagOne==null){
            $request->session()->flash("msg_error", "برچسب اخلاقی اصلی مورد نظر پیدا نشد!");
            return redirect()->route('parent_tag_ones');
        }

        $parentTagOne->is_deleted = true;
        $parentTagOne->save();

        $request->session()->flash("msg_success", "برچسب  اخلاقی اصلی با موفقیت حذف شد.");
        return redirect()->route('parent_tag_ones');
    }
}
