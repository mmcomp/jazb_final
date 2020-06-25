<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\SaleSuggestion;
use App\Source;
use App\Product;
use App\School;
use App\Tag;

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
        $products = Product::where('is_deleted', false)->get();
        $sources = Source::where('is_deleted', false)->get();
        $moralTags = Tag::where('type', 'moral')->where('is_deleted', false)->orderBy('name')->get();
        $needTags = Tag::where('type', 'need')->where('is_deleted', false)->orderBy('name')->get();
        $schools = School::where('is_deleted', false)->get();
        $saleSuggestion = new SaleSuggestion;
        if($request->getMethod()=='GET'){
            return view('sale_suggestions.create', [
                "saleSuggestion"=>$saleSuggestion,
                "products"=>$products,
                "sources"=>$sources,
                "moralTags"=>$moralTags,
                "needTags"=>$needTags,
                "schools"=>$schools
            ]);
        }

        $saleSuggestion->name = $request->input('name', '');
        $saleSuggestion->if_products_id = $request->input('if_products_id');
        $saleSuggestion->if_moral_tags_id = $request->input('if_moral_tags_id');
        $saleSuggestion->if_need_tags_id = $request->input('if_need_tags_id');
        $saleSuggestion->if_schools_id = $request->input('if_schools_id');
        $saleSuggestion->if_last_year_grade = $request->input('if_last_year_grade');
        $saleSuggestion->if_avarage = $request->input('if_avarage');
        $saleSuggestion->if_sources_id = $request->input('if_sources_id');
        $saleSuggestion->users_id = Auth::user()->id;
        $saleSuggestion->then_product1_id = $request->input('then_product1_id');
        $saleSuggestion->then_product2_id = $request->input('then_product2_id');
        $saleSuggestion->then_product3_id = $request->input('then_product3_id');
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط با موفقیت افزوده شد.");
        return redirect()->route('sale_suggestions');
    }

    public function edit(Request $request, $id)
    {;
        $saleSuggestion = SaleSuggestion::where('id', $id)->where('is_deleted', false)->first();
        if($saleSuggestion==null){
            $request->session()->flash("msg_error", "شرط مورد نظر پیدا نشد!");
            return redirect()->route('sale_suggestions');
        }

        $products = Product::where('is_deleted', false)->get();
        $sources = Source::where('is_deleted', false)->get();
        $moralTags = Tag::where('type', 'moral')->where('is_deleted', false)->orderBy('name')->get();
        $needTags = Tag::where('type', 'need')->where('is_deleted', false)->orderBy('name')->get();
        $schools = School::where('is_deleted', false)->get();
        if($request->getMethod()=='GET'){
            return view('sale_suggestions.create', [
                "saleSuggestion"=>$saleSuggestion,
                "products"=>$products,
                "sources"=>$sources,
                "moralTags"=>$moralTags,
                "needTags"=>$needTags,
                "schools"=>$schools
            ]);
        }

        $saleSuggestion->name = $request->input('name', '');
        $saleSuggestion->if_products_id = $request->input('if_products_id');
        $saleSuggestion->if_moral_tags_id = $request->input('if_moral_tags_id');
        $saleSuggestion->if_need_tags_id = $request->input('if_need_tags_id');
        $saleSuggestion->if_schools_id = $request->input('if_schools_id');
        $saleSuggestion->if_last_year_grade = $request->input('if_last_year_grade');
        $saleSuggestion->if_avarage = $request->input('if_avarage');
        $saleSuggestion->if_sources_id = $request->input('if_sources_id');
        $saleSuggestion->users_id = Auth::user()->id;
        $saleSuggestion->then_product1_id = $request->input('then_product1_id');
        $saleSuggestion->then_product2_id = $request->input('then_product2_id');
        $saleSuggestion->then_product3_id = $request->input('then_product3_id');
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط با موفقیت ویرایش شد.");
        return redirect()->route('sale_suggestions');
    }

    public function delete(Request $request, $id)
    {
        $saleSuggestion = SaleSuggestion::where('id', $id)->where('is_deleted', false)->first();
        if($saleSuggestion==null){
            $request->session()->flash("msg_error", "شرط مورد نظر پیدا نشد!");
            return redirect()->route('sale_suggestions');
        }

        $saleSuggestion->is_deleted = true;
        $saleSuggestion->save();

        $request->session()->flash("msg_success", "شرط با موفقیت حذف شد.");
        return redirect()->route('sale_suggestions');
    }
}
