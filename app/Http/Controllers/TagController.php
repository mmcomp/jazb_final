<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $tags = Tag::where('type', 'moral')->with('user')->with('parent')->orderBy('name')->get();
        $tag = new Tag;
        if($request->getMethod()=='GET'){
            return view('tags.create', [
                "tags"=>$tags,
                "tag"=>$tag
            ]);
        }

        $tag->name = $request->input('name', '');
        $tag->parent_id = (int)$request->input('parent_id', 0);
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت افزوده شد.");
        return redirect()->route('tags');
    }
}
