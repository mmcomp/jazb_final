<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;

class TagController extends Controller
{
    public function index(){
        $tags = Tag::where('type', 'moral')->with('user')->with('parent')->orderBy('name')->get();



        return view('tags.index',[
            'tags' => $tags,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $tags = Tag::where('type', 'moral')->where('is_delete', false)->with('user')->with('parent')->orderBy('name')->get();
        $tag = new Tag;
        if($request->getMethod()=='GET'){
            return view('tags.create', [
                "tags"=>$tags,
                "tag"=>$tag
            ]);
        }

        $tag->name = $request->input('name', '');
        $tag->parent_id = (int)$request->input('parent_id', 0);
        $tag->users_id = Auth::user()->id;
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت افزوده شد.");
        return redirect()->route('tags');
    }

    public function edit(Request $request, $id)
    {
        $tags = Tag::where('type', 'moral')->with('user')->with('parent')->orderBy('name')->get();
        $tag = Tag::where('id', $id)->where('is_delete', false)->first();
        if($tag==null){
            $request->session()->flash("msg_error", "برچسب مورد نظر پیدا نشد!");
            return redirect()->route('tags');
        }
        if($request->getMethod()=='GET'){
            return view('tags.create', [
                "tags"=>$tags,
                "tag"=>$tag
            ]);
        }

        $tag->name = $request->input('name', '');
        $tag->parent_id = (int)$request->input('parent_id', 0);
        $tag->users_id = Auth::user()->id;
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت ویرایش شد.");
        return redirect()->route('tags');
    }

    public function delete(Request $request, $id)
    {
        $tag = Tag::where('id', $id)->where('is_delete', false)->first();
        if($tag==null){
            $request->session()->flash("msg_error", "برچسب مورد نظر پیدا نشد!");
            return redirect()->route('tags');
        }

        $tag->is_deleted = true;
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت حذف شد.");
        return redirect()->route('tags');
    }
}
