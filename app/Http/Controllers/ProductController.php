<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Collection;

class ProductController extends Controller
{
    public function index(){
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();

        return view('products.index',[
            'products' => $products,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $collections = Collection::where('is_deleted', false)->orderBy('name')->get();
        $product = new Product;
        if($request->getMethod()=='GET'){
            return view('products.create', [
                "collections"=>$collections,
                "product"=>$product
            ]);
        }

        $product->name = $request->input('name', '');
        $product->collections_id = (int)$request->input('collections_id', 0);
        $product->save();

        $request->session()->flash("msg_success", "محصول با موفقیت افزوده شد.");
        return redirect()->route('products');
    }

    public function edit(Request $request, $id)
    {
        $collections = Collection::where('is_deleted', false)->orderBy('name')->get();
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
}
