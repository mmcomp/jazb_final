<?php

namespace App\Http\Controllers;

use App\Commission;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $commissions = Commission::where('is_deleted',false)->where('users_id',$id)->get();
        return view('commission.index')->with([
          'commissions' => $commissions,
          'id' => $id,
          'msg_success' => request()->session()->get('msg_success'),
          'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$id)
    {
        $products = Product::where('is_deleted',false)->get();
        if($request->getMethod() == "GET"){
            return view('commission.create')->with([
                'products' => $products,
            ]);
        }
        $c = new Commission;
        $c->users_id = $id;
        $c->users_saver_id = Auth::user()->id;
        $c->products_id = $request->input('products_id');
        $c->commission = $request->input('commission');
        try{
            $c->save();
            return redirect()->route('commission',['id' => $id]);
        }catch(Exception $e){
            dd($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id,$supporters_id)
    {
        $products = Product::where('is_deleted',false)->get();
        $c = Commission::where('is_deleted',false)->where('id',$id)->first();
        if($c==null){
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد.");
            return redirect()->route('user_alls');
        }
        if($request->getMethod()=='GET'){
            return view('commission.create', [
                "c" => $c,
                "products" => $products
            ]);
        }
        $c->users_id = $supporters_id;
        $c->users_saver_id = Auth::user()->id;
        $c->products_id = $request->input('products_id');
        $c->commission = $request->input('commission');
        try{
            $c->save();
            return redirect()->route('commission',['id' => $supporters_id]);
        }catch(Exception $e){
            dd($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $commission = Commission::where('id',$id)->where('is_deleted',false)->first();
        $commission->is_deleted = true;
        if($commission==null){
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد.");
            return redirect()->back();
        }
        $commission->is_deleted = true;
        try{
            $commission->save();
            $request->session()->flash("msg_success", "ارتباط با موفقیت حذف شد.");
            return redirect()->back();
        }catch(Exception $e){
            dd($e);
        }
    }
}
