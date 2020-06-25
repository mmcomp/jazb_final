<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\SaleSuggestion;

use Exception;

class SaleSuggestionController extends Controller
{
    public function index(){
        $saleSuggestions = SaleSuggestion::where('is_deleted', false)->orderBy('name')->get();

        return view('sale_suggestions.index',[
            'saleSuggestions' => $saleSuggestions,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $saleSuggestion = new SaleSuggestion;
        if($request->getMethod()=='GET'){
            return view('sale_suggestions.create', [
                "saleSuggestion"=>$saleSuggestion
            ]);
        }

        $saleSuggestion->name = $request->input('name', '');
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط. با موفقیت افزوده شد.");
        return redirect()->route('sale_suggestions');
    }

    public function edit(Request $request, $id)
    {
        $saleSuggestion = SaleSuggestion::where('id', $id)->where('is_deleted', false)->first();
        if($saleSuggestion==null){
            $request->session()->flash("msg_error", "شرط. مورد نظر پیدا نشد!");
            return redirect()->route('sale_suggestions');
        }

        if($request->getMethod()=='GET'){
            return view('sale_suggestions.create', [
                "saleSuggestion"=>$saleSuggestion
            ]);
        }

        $saleSuggestion->name = $request->input('name', '');
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط. با موفقیت ویرایش شد.");
        return redirect()->route('sale_suggestions');
    }

    public function delete(Request $request, $id)
    {
        $saleSuggestion = SaleSuggestion::where('id', $id)->where('is_deleted', false)->first();
        if($saleSuggestion==null){
            $request->session()->flash("msg_error", "شرط. مورد نظر پیدا نشد!");
            return redirect()->route('sale_suggestions');
        }

        $saleSuggestion->is_deleted = true;
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط. با موفقیت حذف شد.");
        return redirect()->route('sale_suggestions');
    }
}
