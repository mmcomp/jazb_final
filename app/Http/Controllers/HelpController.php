<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Help;

use Exception;

class HelpController extends Controller
{
    public function index(){
        $helps = Help::orderBy('created_at')->get();

        return view('helps.index',[
            'helps' => $helps,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function grid(){
        $helps = Help::orderBy('created_at', 'desc')->get();

        return view('helps.grid',[
            'helps' => $helps,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $help = new Help;
        if($request->getMethod()=='GET'){
            return view('helps.create', [
                "help"=>$help
            ]);
        }

        $help->name = $request->input('name');
        $help->link = $request->input('link');
        $help->save();

        $request->session()->flash("msg_success", "آموزش با موفقیت افزوده شد.");
        return redirect()->route('helps');
    }

    public function edit(Request $request, $id)
    {
        $help = Help::where('id', $id)->first();
        if($help==null){
            $request->session()->flash("msg_error", "آموزش مورد نظر پیدا نشد!");
            return redirect()->route('helps');
        }

        if($request->getMethod()=='GET'){
            return view('helps.create', [
                "help"=>$help
            ]);
        }

        $help->name = $request->input('name');
        $help->link = $request->input('link');
        $help->save();

        $request->session()->flash("msg_success", "آموزش با موفقیت ویرایش شد.");
        return redirect()->route('helps');
    }

    public function delete(Request $request, $id)
    {
        $help = Help::where('id', $id)->first();
        if($help==null){
            $request->session()->flash("msg_error", "آموزش مورد نظر پیدا نشد!");
            return redirect()->route('helps');
        }

        $help->delete();

        $request->session()->flash("msg_success", "آموزش با موفقیت حذف شد.");
        return redirect()->route('helps');
    }
}
