<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Source;

class SourceController extends Controller
{
    public function index(){
        $sources = Source::where('is_deleted', false)->orderBy('name')->get();

        return view('sources.index',[
            'sources' => $sources,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $source = new Source;
        if($request->getMethod()=='GET'){
            return view('sources.create', [
                "source"=>$source
            ]);
        }

        $source->name = $request->input('name', '');
        $source->number_count = (int)$request->input('number_count', 0);
        $source->description = $request->input('description');
        $source->save();

        $request->session()->flash("msg_success", "منبع با موفقیت افزوده شد.");
        return redirect()->route('sources');
    }

    public function edit(Request $request, $id)
    {
        $source = Source::where('id', $id)->where('is_deleted', false)->first();
        if($source==null){
            $request->session()->flash("msg_error", "منبع مورد نظر پیدا نشد!");
            return redirect()->route('sources');
        }

        if($request->getMethod()=='GET'){
            return view('sources.create', [
                "source"=>$source
            ]);
        }

        $source->name = $request->input('name', '');
        $source->number_count = (int)$request->input('number_count', 0);
        $source->description = $request->input('description');
        $source->save();

        $request->session()->flash("msg_success", "منبع با موفقیت ویرایش شد.");
        return redirect()->route('sources');
    }

    public function delete(Request $request, $id)
    {
        $source = Source::where('id', $id)->where('is_deleted', false)->first();
        if($source==null){
            $request->session()->flash("msg_error", "منبع مورد نظر پیدا نشد!");
            return redirect()->route('sources');
        }

        $source->is_deleted = true;
        $source->save();

        $request->session()->flash("msg_success", "منبع با موفقیت حذف شد.");
        return redirect()->route('sources');
    }
}
