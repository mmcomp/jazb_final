<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Collection;
use App\Student;
use App\ClassRoom;
use App\StudentClassRoom;

use Exception;
use Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $products = Product::where('is_deleted', false)->with('collection');
        $name = null;
        if ($request->getMethod() == 'POST') {
            if($request->input('name') != null){
                $products = $products->where('name','like','%'.trim($request->input('name','')).'%');
            }
            if($request->input('isPrivate')!= null) {
                if($request->input('isPrivate') != "all") {
                    $products = $products->where('is_private', $request->input('isPrivate'));
                }
            }
        }
        if($request->getMethod() == 'GET'){
            return view('products.index',[
                'route' => 'products',
                'products' => $products->get(),
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else {
            $count = $products->count();
            $req =  $request->all();
            if(!isset($req['start'])){
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            if($columnName != 'row' && $columnName != 'end'){
                $products = $products->orderBy($columnName,$columnSortOrder)
                ->where('is_deleted',false)
                ->with('collection')
                ->select('products.*')
                ->skip($req['start'])
                ->take($req['length'])
                ->get();
            }
            foreach($products as $index => $product){
                $products[$index]->parents = "-";
                if($product->collection) {
                    $parents = $product->collection->parents();
                    $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                    $products[$index]->parents = $name;
                }
            }
            $data = [];

            foreach($products as $index => $item){
                if($item->collection) {
                    $parents = $item->collection->parents();
                    $name = ($parents!='')?$parents : $item->collection->name;
                    $products[$index]->parents = $name;
                }
                $data[] = [
                    "row" => $index +1,
                    "id" => $item->id,
                    "name" => $item->name,
                    "collections_id" => $item->parents,
                    "is_private" => $item->is_private ? 'خصوصی' : 'عمومی',
                    "price" => number_format($item->price),
                    "end" => '<a class="btn btn-primary" href="'.route('product_edit',$item->id).'"> ویرایش</a>
                     <a class="btn btn-danger" onclick="destroy(event)" href="'.route('product_delete',$item->id).'">حذف</a>'
                ];
            }

            $result = [
                "draw" => $req['draw'],
                "data" => $data,
                "recordsTotal" => $count,
                "recordsFiltered" => $count
            ];

            return $result;
        }
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

        $productCollection = new Collection;
        $productCollection->name = $request->input('name', '');
        $productCollection->parent_id = (int)$request->input('collections_id', 0);
        $productCollection->save();

        $product->name = $request->input('name', '');
        $product->collections_id = $productCollection->id;
        $product->price = (int)$request->input('price', 0);
        $product->is_private = $request->input('private');
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
        $product->price = (int)$request->input('price', 0);
        $product->is_private = $request->input('private');
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
                Log::info("addproduct". json_encode($product));
                continue;
            }
            if(isset($product['woo_id']) && (int)$product['woo_id']>0) {
                $productObject = Product::where('woo_id', (int)$product['woo_id'])->first();
                if($productObject == null) {
                    $productObject = new Product;
                }
            }else {
                $productObject = new Product;
            }
            foreach($product as $key=>$value){
                $productObject->$key = $value;
            }
            try{
                $productObject->save();
                $ids[] = $productObject->woo_id;
            }catch(Exception $e){
                $fails[] = $product;
                Log::info(json_encode($e));
            }
        }
        return [
            "added_ids" => $ids,
            "fails" => $fails
        ];
    }

    public function apiDeleteProducts(Request $request){
        $products = $request->input('products', []);
        $ids = [];
        $fails = [];
        foreach($products as $product){
            if(!isset($product['woo_id'])){
                $fails[] = $product;
                continue;
            }
            $productObject = Product::where('woo_id', $product['woo_id'])->with('classrooms')->first();
            if($productObject!=null){
                $productObject->is_deleted = true;
                $productDelete = false;
                try{
                    $productObject->save();
                    $ids[] = $productObject->woo_id;
                    $productDelete = true;
                }catch(Exception $e){
                    $fails[] = $product;
                }
                if($productDelete) {
                    if($productObject->classrooms) {
                        foreach($productObject->classrooms as $classroom) {
                            $classRoomObject = ClassRoom::where('id', $classroom->id)->first();
                            $classRoomDeleted = false;
                            if($classRoomObject) {
                                $classRoomObject->is_deleted = true;
                                $classRoomDeleted = true;
                                try{
                                    $classRoomObject->save();
                                }catch(Exception $e){
                                }
                                if($classRoomDeleted) {
                                    StudentClassRoom::where('class_rooms_id', $classroom->id)->delete();
                                }
                            }
                        }
                    }
                }
            }else {
                $fails[] = $product;
            }
        }
        return [
            "deleted_ids" => $ids,
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
