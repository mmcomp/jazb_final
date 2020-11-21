<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ClassRoom;
use App\Product;


class ClassRoomController extends Controller
{
    public function index(){
        $classRooms = ClassRoom::where("is_deleted", false)->orderBy('name')->get();

        return view('class_rooms.index',[
            'classRooms' => $classRooms,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $classRoom = new ClassRoom;
        $products = Product::where('is_deleted', false)->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        if($request->getMethod()=='GET'){
            return view('class_rooms.create', [
                "classRoom"=>$classRoom,
                "products"=>$products
            ]);
        }

        $classRoom->name = $request->input('name', '');
        $classRoom->description = $request->input('description');
        if($request->input('products_id') && (int)$request->input('products_id')>0) {
            $classRoom->products_id = (int)$request->input('products_id');
        }
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت افزوده شد.");
        return redirect()->route('class_rooms');
    }

    public function edit(Request $request, $id)
    {
        $classRoom = ClassRoom::where('id', $id)->where("is_deleted", false)->first();
        if($classRoom==null){
            $request->session()->flash("msg_error", "کلاس مورد نظر پیدا نشد!");
            return redirect()->route('class_rooms');
        }

        $products = Product::where('is_deleted', false)->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        if($request->getMethod()=='GET'){
            return view('class_rooms.create', [
                "classRoom"=>$classRoom,
                "products"=>$products
            ]);
        }

        $classRoom->name = $request->input('name', '');
        $classRoom->description = $request->input('description');
        if($request->input('products_id') && (int)$request->input('products_id')>0) {
            $classRoom->products_id = (int)$request->input('products_id');
        }
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت ویرایش شد.");
        return redirect()->route('class_rooms');
    }

    public function delete(Request $request, $id)
    {
        $classRoom = ClassRoom::where('id', $id)->where("is_deleted", false)->first();
        if($classRoom==null){
            $request->session()->flash("msg_error", "کلاس مورد نظر پیدا نشد!");
            return redirect()->route('class_rooms');
        }

        $classRoom->is_deleted = true;
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت حذف شد.");
        return redirect()->route('class_rooms');
    }
}
