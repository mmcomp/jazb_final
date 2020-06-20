<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Temperature;

class TemperatureController extends Controller
{
    public function index(){
        $temperatures = Temperature::where('is_deleted', false)->orderBy('name')->get();

        return view('temperatures.index',[
            'temperatures' => $temperatures,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $temperature = new Temperature();
        if($request->getMethod()=='GET'){
            return view('temperatures.create', [
                "temperature"=>$temperature
            ]);
        }

        $temperature->name = $request->input('name', '');
        $temperature->type = $request->input('type', 'global');
        $temperature->status = $request->input('status', 'hot');
        $temperature->users_id = Auth::user()->id;
        $temperature->save();

        $request->session()->flash("msg_success", "داغ/سرد با موفقیت افزوده شد.");
        return redirect()->route('temperatures');
    }

    public function edit(Request $request, $id)
    {
        $temperature = Temperature::where('id', $id)->where('is_deleted', false)->first();
        if($temperature==null){
            $request->session()->flash("msg_error", "داغ/سرد مورد نظر پیدا نشد!");
            return redirect()->route('temperatures');
        }
        if($request->getMethod()=='GET'){
            return view('temperatures.create', [
                "temperature"=>$temperature
            ]);
        }
        $temperature->name = $request->input('name', '');
        $temperature->type = $request->input('type', 'global');
        $temperature->status = $request->input('status', 'hot');
        $temperature->users_id = Auth::user()->id;
        $temperature->save();

        $request->session()->flash("msg_success", "داغ/سرد  با موفقیت ویرایش شد.");
        return redirect()->route('temperatures');
    }

    public function delete(Request $request, $id)
    {
        $temperature = Temperature::where('id', $id)->where('is_deleted', false)->first();
        if($temperature==null){
            $request->session()->flash("msg_error", "داغ/سرد مورد نظر پیدا نشد!");
            return redirect()->route('temperatures');
        }

        $temperature->is_deleted = true;
        $temperature->save();

        $request->session()->flash("msg_success", "داغ/سرد با موفقیت حذف شد.");
        return redirect()->route('temperatures');
    }
}
