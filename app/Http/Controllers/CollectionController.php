<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Collection;

class CollectionController extends Controller
{
    public function index(){
        $collections = Collection::where('is_deleted', false)->with('parent')->orderBy('name')->get();

        foreach($collections as $index => $collection){
            $collections[$index]->parents = $collection->parents();
        }
        // dd($collections[2]->parents());

        return view('collections.index',[
            'collections' => $collections,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $collections = Collection::where('is_deleted', false)->orderBy('name')->get();
        $collection = new Collection;
        foreach($collections as $index => $collection){
            $collections[$index]->parents = $collection->parents();
            $collections[$index]->name = ($collections[$index]->parents!='')?$collections[$index]->parents . "->" . $collections[$index]->name : $collections[$index]->name;
        }
        if($request->getMethod()=='GET'){
            return view('collections.create', [
                "collections"=>$collections,
                "collection"=>$collection
            ]);
        }

        $collection->name = $request->input('name', '');
        $collection->parent_id = (int)$request->input('parent_id', 0);
        $collection->save();

        $request->session()->flash("msg_success", "دسته با موفقیت افزوده شد.");
        return redirect()->route('collections');
    }

    public function edit(Request $request, $id)
    {
        $collections = Collection::where('is_deleted', false)->where('id', '!=', $id)->orderBy('name')->get();
        $collection = Collection::where('id', $id)->where('is_deleted', false)->first();
        foreach($collections as $index => $collection){
            $collections[$index]->parents = $collection->parents();
            $collections[$index]->name = ($collections[$index]->parents!='')?$collections[$index]->parents . "->" . $collections[$index]->name : $collections[$index]->name;
        }
        if($collection==null){
            $request->session()->flash("msg_error", "دسته مورد نظر پیدا نشد!");
            return redirect()->route('collections');
        }
        if($request->getMethod()=='GET'){
            return view('collections.create', [
                "collections"=>$collections,
                "collection"=>$collection
            ]);
        }

        $parent_id = (int)$request->input('parent_id', 0);
        if($parent_id>0){
            $parent = Collection::where('id', $parent_id)->where('is_deleted', false)->first();
            if($parent==null){
                $request->session()->flash("msg_error", "والد نا معتبر است!");
                return redirect()->route('collections');
            }

            if($parent->parent_id == $collection->id){
                $request->session()->flash("msg_error", "دسته نمی تواند با این والد ثبت شود!");
                return redirect()->route('collections');
            }
        }

        $collection->name = $request->input('name', '');
        $collection->parent_id = $parent_id;
        $collection->save();

        $request->session()->flash("msg_success", "دسته با موفقیت ویرایش شد.");
        return redirect()->route('collections');
    }

    public function delete(Request $request, $id)
    {
        $collection = Collection::where('id', $id)->where('is_deleted', false)->first();
        if($collection==null){
            $request->session()->flash("msg_error", "دسته مورد نظر پیدا نشد!");
            return redirect()->route('collections');
        }

        $collection->is_deleted = true;
        $collection->save();

        $request->session()->flash("msg_success", "دسته با موفقیت حذف شد.");
        return redirect()->route('collections');
    }
}
