<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use App\NeedTagParentOne;
use App\NeedTagParentTwo;
use App\NeedTagParentThree;
use App\NeedTagParentFour;
use App\Product;
use Exception;

class NeedTagController extends Controller
{
    public function index(){
        $tags = Tag::where('type', 'need')->where('is_deleted', false)->with('user')->with('user')->with('need_parent_one')->with('need_parent_two')->with('need_parent_three')->with('need_parent_four')->with('product')->orderBy('name')->get();

        return view('need_tags.index',[
            'tags' => $tags,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $tags = Tag::where('type', 'need')->where('is_deleted', false)->with('user')->with('parent')->orderBy('name')->get();
        $tagParentOnes = NeedTagParentOne::where('is_deleted', false)->orderBy('name')->get();
        $tagParentTwos = NeedTagParentTwo::where('is_deleted', false)->orderBy('name')->get();
        $tagParentThrees = NeedTagParentThree::where('is_deleted', false)->orderBy('name')->get();
        $tagParentFours = NeedTagParentFour::where('is_deleted', false)->orderBy('name')->get();
        $products = Product::where('is_deleted', false)->get();
        $tag = new Tag;
        if($request->getMethod()=='GET'){
            return view('need_tags.create', [
                "tags"=>$tags,
                "tagParentOnes"=>$tagParentOnes,
                "tagParentTwos"=>$tagParentTwos,
                "tagParentThrees"=>$tagParentThrees,
                "tagParentFours"=>$tagParentFours,
                "products"=>$products,
                "tag"=>$tag
            ]);
        }

        $product =  Product::find($request->input('products_id'));
        if(!$product) {
            $request->session()->flash("msg_error", "برچسب با موفقیت افزوده نشد چون محصول پیدا نشد");
            return redirect()->route('need_tags');
        }
        $tag->name = $product->name;
        $tag->products_id = $request->input('products_id');
        $tag->need_parent1 = (int)$request->input('need_parent1', 0);
        $tag->need_parent2 = (int)$request->input('need_parent2', 0);
        $tag->need_parent3 = (int)$request->input('need_parent3', 0);
        $tag->need_parent4 = (int)$request->input('need_parent4', 0);
        $tag->parent1 = 0;
        $tag->parent2 = 0;
        $tag->parent3 = 0;
        $tag->parent4 = 0;
        $tag->users_id = Auth::user()->id;
        $tag->type = 'need';
        try{
            $tag->save();
        }catch(Exception $error)
        {
            // dd($error);
            $request->session()->flash("msg_error", "برچسب با موفقیت افزوده نشد.");
            return redirect()->route('need_tags');
        }

        $request->session()->flash("msg_success", "برچسب با موفقیت افزوده شد.");
        return redirect()->route('need_tags');
    }

    public function edit(Request $request, $id)
    {
        $tags = Tag::where('type', 'need')->where('id', '!=', $id)->with('user')->with('parent')->orderBy('name')->get();
        $tagParentOnes = NeedTagParentOne::where('is_deleted', false)->orderBy('name')->get();
        $tagParentTwos = NeedTagParentTwo::where('is_deleted', false)->orderBy('name')->get();
        $tagParentThrees = NeedTagParentThree::where('is_deleted', false)->orderBy('name')->get();
        $tagParentFours = NeedTagParentFour::where('is_deleted', false)->orderBy('name')->get();
        $tag = Tag::where('id', $id)->where('is_deleted', false)->first();
        if($tag==null){
            $request->session()->flash("msg_error", "برچسب مورد نظر پیدا نشد!");
            return redirect()->route('need_tags');
        }
        $products = Product::where('is_deleted', false)->get();
        if($request->getMethod()=='GET'){
            return view('need_tags.create', [
                "tags"=>$tags,
                "tagParentOnes"=>$tagParentOnes,
                "tagParentTwos"=>$tagParentTwos,
                "tagParentThrees"=>$tagParentThrees,
                "tagParentFours"=>$tagParentFours,
                "products"=>$products,
                "tag"=>$tag
            ]);
        }


        $tag->name = $request->input('name', '');
        $tag->products_id = $request->input('products_id');
        $tag->need_parent1 = (int)$request->input('need_parent1', 0);
        $tag->need_parent2 = (int)$request->input('need_parent2', 0);
        $tag->need_parent3 = (int)$request->input('need_parent3', 0);
        $tag->need_parent4 = (int)$request->input('need_parent4', 0);
        $tag->parent1 = 0;
        $tag->parent2 = 0;
        $tag->parent3 = 0;
        $tag->parent4 = 0;
        $tag->users_id = Auth::user()->id;
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت ویرایش شد.");
        return redirect()->route('need_tags');
    }

    public function delete(Request $request, $id)
    {
        $tag = Tag::where('id', $id)->where('is_deleted', false)->first();
        if($tag==null){
            $request->session()->flash("msg_error", "برچسب مورد نظر پیدا نشد!");
            return redirect()->route('need_tags');
        }

        $tag->is_deleted = true;
        $tag->save();

        $request->session()->flash("msg_success", "برچسب با موفقیت حذف شد.");
        return redirect()->route('need_tags');
    }
    /**
     * get products using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getProducts(Request $request)
    {

        $search = trim($request->search);
        if ($search == '') {
            $products = Product::orderby('id', 'desc')->select('id', 'name','is_deleted')->where('is_deleted',false)->get();
        } else {
            $products = Product::select('id','name','is_deleted')->where(
                'is_deleted',
                false
            )->where(function ($query) use ($search) {
                $query->where('name','like','%'.$search.'%');
            })->orderby('id','desc')->get();
        }
        $response = array();
        foreach ($products as $product) {
            $response[] = array(
                "id" => $product->id,
                "text" => $product->name
            );
        }
        $response[] = [
            "id" => 0,
            "text" => "-"
        ];
        return $response;
    }
     /**
     * get needtag1 using select2 with ajax
     *
     *
     * @return \Illuminate\Http\Response
     */
    //---------------------AJAX-----------------------------------
    public function getNeedTag1(Request $request)
    {

        $search = trim($request->search);
        if ($search == '') {
            $needtag1 = NeedTagParentOne::orderby('id', 'desc')->select('id', 'name','is_deleted')->where('is_deleted',false)->get();
        } else {
            $needtag1 = NeedTagParentOne::select('id','name','is_deleted')->where(
                'is_deleted',
                false
            )->where(function ($query) use ($search) {
                $query->where('name','like','%'.$search.'%');
            })->orderby('id','desc')->get();
        }
        $response = array();
        foreach ($needtag1 as $item) {
            $response[] = array(
                "id" => $item->id,
                "text" => $item->name
            );
        }
        $response[] = [
            "id" => 0,
            "text" => "-"
        ];
        return $response;
    }
}
