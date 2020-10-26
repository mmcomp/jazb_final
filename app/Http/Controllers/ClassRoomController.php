<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ClassRoom;


class ClassRoomController extends Controller
{
    public function index(){
        $classRooms = ClassRoom::where("is_deleted", false)->orderBy('name')->get();

        return view('class_rooms.index',[
            'classRooms' => $classRooms,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $classRoom = new ClassRoom;
        if($request->getMethod()=='GET'){
            return view('class_rooms.create', [
                "classRoom"=>$classRoom
            ]);
        }

        $classRoom->name = $request->input('name', '');
        $classRoom->description = $request->input('description');
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت افزوده شد.");
        return redirect()->route('class_rooms');
    }

    public function edit(Request $request, $id)
    {
        $classRoom = ClassRoom::where('id', $id)->where("is_deleted", false)->first();
        if($classRoom==null){
            $request->session()->flash("msg_error", "کلاس مورد نظر پیدا نشد!");
            return redirect()->route('class_rooms');
        }

        if($request->getMethod()=='GET'){
            return view('class_rooms.create', [
                "classRoom"=>$classRoom
            ]);
        }

        $classRoom->name = $request->input('name', '');
        $classRoom->description = $request->input('description');
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت ویرایش شد.");
        return redirect()->route('class_rooms');
    }

    public function delete(Request $request, $id)
    {
        $classRoom = ClassRoom::where('id', $id)->where("is_deleted", false)->first();
        if($classRoom==null){
            $request->session()->flash("msg_error", "کلاس مورد نظر پیدا نشد!");
            return redirect()->route('class_rooms');
        }

        $classRoom->is_deleted = true;
        $classRoom->save();

        $request->session()->flash("msg_success", "کلاس با موفقیت حذف شد.");
        return redirect()->route('class_rooms');
    }
}
