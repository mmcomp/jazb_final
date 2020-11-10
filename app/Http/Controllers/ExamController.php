<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exam;


class ExamController extends Controller
{
    public function index(){
        $exams = Exam::where("is_deleted", false)->orderBy('name')->get();

        return view('exams.index',[
            'exams' => $exams,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function create(Request $request)
    {
        $exam = new Exam;
        if($request->getMethod()=='GET'){
            return view('exams.create', [
                "exam"=>$exam
            ]);
        }

        $exam->name = $request->input('name', '');
        $exam->description = $request->input('description');
        $exam->users_id = Auth::user()->id;
        if($request->file('question_pdf')){
            $filenameNoextension = now()->timestamp;
            $filename = $filenameNoextension . '.' . $request->file('question_pdf')->extension();
            $exam->question_pdf = $request->file('question_pdf')->storeAs('examp_questions', $filename, 'public_uploads');
            $imageDirectory = public_path('uploads') . DIRECTORY_SEPARATOR . 'examp_questions' . DIRECTORY_SEPARATOR . $filenameNoextension;
            $pdfAddress = str_replace('/', DIRECTORY_SEPARATOR, public_path('uploads') . DIRECTORY_SEPARATOR . $exam->question_pdf);
            $pdfAddress = str_replace('\\', DIRECTORY_SEPARATOR, $pdfAddress);
            $cmd = env('PDF2JPG');
            $cmd = str_replace('$pdfAddress', $pdfAddress, $cmd);
            $cmd = str_replace('$imageDirectory', $imageDirectory, $cmd);
            mkdir($imageDirectory);
            shell_exec($cmd);
        }
        if($request->file('answer_pdf')){
            $filenameNoextension = now()->timestamp;
            $filename = $filenameNoextension . '.' . $request->file('answer_pdf')->extension();
            $exam->answer_pdf = $request->file('answer_pdf')->storeAs('examp_answers', $filename, 'public_uploads');
            $imageDirectory = public_path('uploads') . DIRECTORY_SEPARATOR . 'examp_answers' . DIRECTORY_SEPARATOR . $filenameNoextension;
            $pdfAddress = str_replace('/', DIRECTORY_SEPARATOR, public_path('uploads') . DIRECTORY_SEPARATOR . $exam->question_pdf);
            $pdfAddress = str_replace('\\', DIRECTORY_SEPARATOR, $pdfAddress);
            $cmd = env('PDF2JPG');
            $cmd = str_replace('$pdfAddress', $pdfAddress, $cmd);
            $cmd = str_replace('$imageDirectory', $imageDirectory, $cmd);
            mkdir($imageDirectory);
            shell_exec($cmd);
        }
        $exam->save();

        $request->session()->flash("msg_success", "آزمون با موفقیت افزوده شد.");
        return redirect()->route('exams');
    }

    public function edit(Request $request, $id)
    {
        $exam = Exam::where('id', $id)->where("is_deleted", false)->first();
        if($exam==null){
            $request->session()->flash("msg_error", "آزمون مورد نظر پیدا نشد!");
            return redirect()->route('exams');
        }

        if($request->getMethod()=='GET'){
            return view('exams.create', [
                "exam"=>$exam
            ]);
        }

        $exam->name = $request->input('name', '');
        $exam->description = $request->input('description');
        if($request->file('question_pdf')){
            $filenameNoextension = now()->timestamp;
            $filename = $filenameNoextension . '.' . $request->file('question_pdf')->extension();
            $exam->question_pdf = $request->file('question_pdf')->storeAs('examp_questions', $filename, 'public_uploads');
            $imageDirectory = public_path('uploads') . DIRECTORY_SEPARATOR . 'examp_questions' . DIRECTORY_SEPARATOR . $filenameNoextension;
            $pdfAddress = str_replace('/', DIRECTORY_SEPARATOR, public_path('uploads') . DIRECTORY_SEPARATOR . $exam->question_pdf);
            $pdfAddress = str_replace('\\', DIRECTORY_SEPARATOR, $pdfAddress);
            $cmd = env('PDF2JPG');
            $cmd = str_replace('$pdfAddress', $pdfAddress, $cmd);
            $cmd = str_replace('$imageDirectory', $imageDirectory, $cmd);
            mkdir($imageDirectory);
            shell_exec($cmd);
        }
        if($request->file('answer_pdf')){
            $filenameNoextension = now()->timestamp;
            $filename = $filenameNoextension . '.' . $request->file('answer_pdf')->extension();
            $exam->answer_pdf = $request->file('answer_pdf')->storeAs('examp_answers', $filename, 'public_uploads');
            $imageDirectory = public_path('uploads') . DIRECTORY_SEPARATOR . 'examp_answers' . DIRECTORY_SEPARATOR . $filenameNoextension;
            $pdfAddress = str_replace('/', DIRECTORY_SEPARATOR, public_path('uploads') . DIRECTORY_SEPARATOR . $exam->question_pdf);
            $pdfAddress = str_replace('\\', DIRECTORY_SEPARATOR, $pdfAddress);
            $cmd = env('PDF2JPG');
            $cmd = str_replace('$pdfAddress', $pdfAddress, $cmd);
            $cmd = str_replace('$imageDirectory', $imageDirectory, $cmd);
            mkdir($imageDirectory);
            shell_exec($cmd);
        }
        $exam->save();

        $request->session()->flash("msg_success", "آزمون با موفقیت ویرایش شد.");
        return redirect()->route('exams');
    }

    public function delete(Request $request, $id)
    {
        $exam = Exam::where('id', $id)->where("is_deleted", false)->first();
        if($exam==null){
            $request->session()->flash("msg_error", "آزمون مورد نظر پیدا نشد!");
            return redirect()->route('exams');
        }

        $exam->is_deleted = true;
        $exam->save();

        $request->session()->flash("msg_success", "آزمون با موفقیت حذف شد.");
        return redirect()->route('exams');
    }
}
