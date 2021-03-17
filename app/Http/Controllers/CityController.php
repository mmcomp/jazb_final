<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\City;
use App\Province;


class CityController extends Controller
{
    public function index(){
        $cities = City::with('province')->where("is_deleted", false)->orderBy('provinces_id')->orderBy('name')->get();

        return view('cities.index',[
            'cities' => $cities,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $provinces = City::orderBy('name')->where("is_deleted", false)->get();
        $city = new City;
        if($request->getMethod()=='GET'){
            return view('cities.create', [
                "provinces"=>$provinces,
                "city"=>$city
            ]);
        }

        $city->name = $request->input('name', '');
        $city->provinces_id = $request->input('provinces_id');
        $city->save();

        $request->session()->flash("msg_success", "شهر با موفقیت افزوده شد.");
        return redirect()->route('cities');
    }

    public function edit(Request $request, $id)
    {
        $city = City::where('id', $id)->where("is_deleted", false)->first();
        if($city==null){
            $request->session()->flash("msg_error", "شهر مورد نظر پیدا نشد!");
            return redirect()->route('products');
        }
        $provinces = Province::orderBy('name')->where("is_deleted", false)->get();

        if($request->getMethod()=='GET'){
            return view('cities.create', [
                "provinces"=>$provinces,
                "city"=>$city
            ]);
        }

        $city->name = $request->input('name', '');
        $city->provinces_id = $request->input('provinces_id');
        $city->save();

        $request->session()->flash("msg_success", "شهر با موفقیت ویرایش شد.");
        return redirect()->route('cities');
    }

    public function delete(Request $request, $id)
    {
        $city = City::where('id', $id)->where('is_deleted', false)->first();
        if($city==null){
            $request->session()->flash("msg_error", "شهر مورد نظر پیدا نشد!");
            return redirect()->route('cities');
        }

        $city->is_deleted = true;
        $city->save();

        $request->session()->flash("msg_success", "شهر با موفقیت حذف شد.");
        return redirect()->route('cities');
    }
}
