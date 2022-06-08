<?php

namespace App\Http\Controllers;

use App\Sanad;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sanads = Sanad::all();

        return view('sanads.index',[
            'sanads' => $sanads,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $sanad = new Sanad;
        
        if($request->getMethod()=='GET'){
            $supportGroupId = Group::getSupport();
            if ($supportGroupId)
                $supportGroupId = $supportGroupId->id;
            $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
            return view('sanads.create', [
                "sanad"=>$sanad,
                "supports"=>$supports,
            ]);
        }

        $sanad->supporter_id = $request->input('supporter_id');
        $sanad->number = $request->input('number');
        $sanad->description = $request->input('description');
        $sanad->total = (int)$request->input('total', 0);
        $sanad->supporter_percent = (int)$request->input('supporter_percent', 0);
        $sanad->type = $request->type && $request->type === "on" ? 1 : -1;
        $sanad->user_id = Auth::user()->id;
        $sanad->save();

        $request->session()->flash("msg_success", "سند با موفقیت افزوده شد.");
        return redirect()->route('sanads');
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
     * @param  \App\Sanad  $sanad
     * @return \Illuminate\Http\Response
     */
    public function show(Sanad $sanad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sanad  $sanad
     * @return \Illuminate\Http\Response
     */
    public function edit(Sanad $sanad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sanad  $sanad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sanad $sanad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sanad  $sanad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sanad $sanad)
    {
        //
    }
}
