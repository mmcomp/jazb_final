<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lesson;


class LessonController extends Controller
{
    public function index(){
        $lessons = Lesson::where("is_deleted", false)->orderBy('name')->get();

        return view('lessons.index',[
            'lessons' => $lessons,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $lesson = new Lesson;
        if($request->getMethod()=='GET'){
            return view('lessons.create', [
                "lesson"=>$lesson
            ]);
        }

        $lesson->name = $request->input('name', '');
        $lesson->description = $request->input('description');
        $lesson->save();

        $request->session()->flash("msg_success", "درس با موفقیت افزوده شد.");
        return redirect()->route('lessons');
    }

    public function edit(Request $request, $id)
    {
        $lesson = Lesson::where('id', $id)->where("is_deleted", false)->first();
        if($lesson==null){
            $request->session()->flash("msg_error", "درس مورد نظر پیدا نشد!");
            return redirect()->route('lessons');
        }

        if($request->getMethod()=='GET'){
            return view('lessons.create', [
                "lesson"=>$lesson
            ]);
        }

        $lesson->name = $request->input('name', '');
        $lesson->description = $request->input('description');
        $lesson->save();

        $request->session()->flash("msg_success", "درس با موفقیت ویرایش شد.");
        return redirect()->route('lessons');
    }

    public function delete(Request $request, $id)
    {
        $lesson = Lesson::where('id', $id)->where("is_deleted", false)->first();
        if($lesson==null){
            $request->session()->flash("msg_error", "درس مورد نظر پیدا نشد!");
            return redirect()->route('lessons');
        }

        $lesson->is_deleted = true;
        $lesson->save();

        $request->session()->flash("msg_success", "درس با موفقیت حذف شد.");
        return redirect()->route('lessons');
    }
}
