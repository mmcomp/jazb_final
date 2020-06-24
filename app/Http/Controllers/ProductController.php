<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Collection;
use App\Student;

use Exception;

class ProductController extends Controller
{
    public function index(){
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        return view('products.index',[
            'products' => $products,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $collections = Collection::where('is_deleted', false)->orderBy('name')->get();
        foreach($collections as $index => $collection){
            $collections[$index]->parents = $collection->parents();
            $collections[$index]->name = ($collections[$index]->parents!='')?$collections[$index]->parents . "->" . $collections[$index]->name : $collections[$index]->name;
        }
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

    //---------------------API------------------------------------
    public function apiAddProducts(Request $request){
        $products = $request->input('products', []);
        $ids = [];
        $fails = [];
        foreach($products as $product){
            if(!isset($product['name']) || !isset($product['collections_id'])){
                $fails[] = $product;
                continue;
            }
            $productObject = new Product;
            foreach($product as $key=>$value){
                $productObject->$key = $value;
            }
            try{
                $productObject->save();
                $ids[] = $productObject->id;
            }catch(Exception $e){
                $fails[] = $product;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }

    //---------------------API------------------------------------
    public function apiAddStudents(Request $request){
        $students = $request->input('students', []);
        $ids = [];
        $fails = [];
        foreach($students as $student){
            if(!isset($student['phone']) || !isset($student['last_name'])){
                $fails[] = $student;
                continue;
            }
            $studentObject = new Student;
            foreach($student as $key=>$value){
                $studentObject->$key = $value;
            }
            try{
                $studentObject->save();
                $ids[] = $studentObject->id;
            }catch(Exception $e){
                $fails[] = $student;
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }
}
