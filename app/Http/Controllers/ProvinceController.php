<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\City;
use App\Province;


class ProvinceController extends Controller
{
    public function index(){
        $provinces = Province::orderBy('name')->get();

        return view('provinces.index',[
            'provinces' => $provinces,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $province = new Province;
        if($request->getMethod()=='GET'){
            return view('provinces.create', [
                "province"=>$province
            ]);
        }

        $province->name = $request->input('name', '');
        $province->save();

        $request->session()->flash("msg_success", "استان با موفقیت افزوده شد.");
        return redirect()->route('provinces');
    }

    public function edit(Request $request, $id)
    {
        $province = Province::where('id', $id)->first();
        if($province==null){
            $request->session()->flash("msg_error", "استان مورد نظر پیدا نشد!");
            return redirect()->route('provinces');
        }

        if($request->getMethod()=='GET'){
            return view('provinces.create', [
                "province"=>$province,
            ]);
        }

        $province->name = $request->input('name', '');
        $province->save();

        $request->session()->flash("msg_success", "استان با موفقیت ویرایش شد.");
        return redirect()->route('provinces');
    }

    public function delete(Request $request, $id)
    {
        $province = Province::where('id', $id)->first();
        if($province==null){
            $request->session()->flash("msg_error", "استان مورد نظر پیدا نشد!");
            return redirect()->route('provinces');
        }

        $province->is_deleted = true;
        $province->save();

        $request->session()->flash("msg_success", "استان با موفقیت حذف شد.");
        return redirect()->route('provinces');
    }
}
