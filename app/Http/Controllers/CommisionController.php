<?php

namespace App\Http\Controllers;

use App\Commision;
use Illuminate\Http\Request;
use Exception;

class CommisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $commisions = Commision::where('users_id',$id)->where('is_deleted',false)->get();
        return view('commision.index')->with([
          'commisions' => $commisions,
          'msg_success' => request()->session()->get('msg_success'),
          'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('1');
        //return view('commision.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $commision = Commision::where('id',$id)->where('is_deleted',false)->first();
        $commision->is_deleted = true;
        if($commision==null){
            $request->session()->flash("msg_error", "ارتباط مورد نظر پیدا نشد.");
            return redirect()->back();
        }
        $commision->is_deleted = true;
        try{
            $commision->save();
            $request->session()->flash("msg_success", "ارتباط با موفقیت حذف شد.");
            return redirect()->back();
        }catch(Exception $e){
            dd($e);
        }
    }
}
