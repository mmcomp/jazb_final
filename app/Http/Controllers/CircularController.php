<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Circular;
use App\CircularUsers;

use Exception;

class CircularController extends Controller
{
    public function index(){
        $circulars = Circular::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        $seenCirculars = CircularUsers::where('users_id', Auth::user()->id)->whereIn('circulars_id', $circulars->pluck('id'))->pluck('circulars_id')->toArray();
        foreach($circulars as $circular){
            if(!in_array($circular->id, $seenCirculars)){
                $unseen = new CircularUsers();
                $unseen->circulars_id = $circular->id;
                $unseen->users_id = Auth::user()->id;
                $unseen->save();
            }
        }
        // dd($seenCirculars);
        return view('circulars.index',[
            'circulars' => $circulars,
            'seenCirculars'=>$seenCirculars,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $circular = new Circular;
        if($request->getMethod()=='GET'){
            return view('circulars.create', [
                "circular"=>$circular
            ]);
        }

        $circular->title = $request->input('title');
        $circular->content = $request->input('content');
        $circular->save();

        $request->session()->flash("msg_success", "بخشنامه با موفقیت افزوده شد.");
        return redirect()->route('circulars');
    }
    /*

    public function edit(Request $request, $id)
    {
        $collections = Collection::where('is_deleted', false)->orderBy('name')->get();
        foreach($collections as $index => $collection){
            $collections[$index]->parents = $collection->parents();
            $collections[$index]->name = ($collections[$index]->parents!='')?$collections[$index]->parents . "->" . $collections[$index]->name : $collections[$index]->name;
        }
        $product = Product::where('id', $id)->where('is_deleted', false)->first();
        if($product==null){
            $request->session()->flash("msg_error", "محصول مورد نظر پیدا نشد!");
            return redirect()->route('products');
        }

        if($request->getMethod()=='GET'){
            return view('products.create', [
                "collections"=>$collections,
                "product"=>$product
            ]);
        }

        $product->name = $request->input('name', '');
        $product->collections_id = (int)$request->input('collections_id', 0);
        $product->price = (int)$request->input('price', 0);
        $product->save();

        $request->session()->flash("msg_success", "محصول با موفقیت ویرایش شد.");
        return redirect()->route('products');
    }

    public function delete(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('is_deleted', false)->first();
        if($product==null){
            $request->session()->flash("msg_error", "محصول مورد نظر پیدا نشد!");
            return redirect()->route('products');
        }

        $product->is_deleted = true;
        $product->save();

        $request->session()->flash("msg_success", "محصول با موفقیت حذف شد.");
        return redirect()->route('products');
    }
    */
}
