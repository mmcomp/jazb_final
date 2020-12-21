<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exam;
use App\Lesson;
use App\Question;

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
            $imageDirectory = public_path('uploads') . DIRECTORY_SEPARATOR . 'examp_questions' . DIRECTORY_SEPARATOR . $filenameNoextension . DIRECTORY_SEPARATOR;
            $pdfAddress = str_replace('/', DIRECTORY_SEPARATOR, public_path('uploads') . DIRECTORY_SEPARATOR . $exam->question_pdf);
            $pdfAddress = str_replace('\\', DIRECTORY_SEPARATOR, $pdfAddress);
            $cmd = env('PDF2JPG');
            $cmd = str_replace('$pdfAddress', $pdfAddress, $cmd);
            $cmd = str_replace('$imageDirectory', $imageDirectory, $cmd);
            mkdir($imageDirectory);
            exec($cmd);
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

    public function questions(Request $request, $id){
        $exam = Exam::where('id', $id)->where("is_deleted", false)->first();
        if($exam==null){
            $request->session()->flash("msg_error", "آزمون مورد نظر پیدا نشد!");
            return redirect()->route('exams');
        }
        $questions = Question::where("is_deleted", false)->where('exams_id', $id)->orderBy('order')->orderBy('lessons_id')->get();

        return view('exams.questions',[
            "exam"=>$exam,
            'questions' => $questions,
            'msg_success' => $request->session()->get('msg_success'),
            'msg_error' => $request->session()->get('msg_error')
        ]);
    }


    public function questionCreate(Request $request, $exam_id)
    {
        $exam = Exam::where('id', $exam_id)->where("is_deleted", false)->first();
        if($exam==null){
            $request->session()->flash("msg_error", "آزمون مورد نظر پیدا نشد!");
            return redirect()->route('exams');
        }
        $image_path = str_replace("/" , DIRECTORY_SEPARATOR, strtolower($exam->question_pdf));
        $image_path = str_replace("\\" , DIRECTORY_SEPARATOR, $image_path);
        $image_path = str_replace(".pdf" , "", $image_path);
        $image_path = public_path('uploads' . DIRECTORY_SEPARATOR .  $image_path );
        $folder = str_replace(".pdf" , "", $exam->question_pdf);
        $images = [];
        if(file_exists($image_path)) {
            $dir = scandir($image_path);
            foreach($dir as $img) {
                if($img != '.' && $img != '..') {
                    $images[] = '/uploads/' . $folder . '/' . $img;
                }
            }
        }
        if(count($images)==0) {
            $request->session()->flash("msg_error", "آزمون مورد نظر تصویر سوال ندارد!");
            return redirect()->route('exam_questions', ['exam_id', $exam->id]);
        }

        $lessons = Lesson::where("is_deleted", false)->get();
        $question = new Question;
        if($request->getMethod()=='GET'){
            return view('exams.question_create', [
                "exam"=>$exam,
                "question"=>$question,
                "images"=>$images,
                "lessons"=>$lessons
            ]);
        }

        if($request->file('selected_area')){
            $filenameNoextension = now()->timestamp;
            $filename = $filenameNoextension . '.' . $request->file('selected_area')->extension();
            $image_path = $request->file('selected_area')->storeAs('examp_images', $filename, 'public_uploads');
            return [
                "status"=>true,
                "image_path"=>$image_path
            ];
        }

        $question->image_path = $request->input('selected_image');
        $question->exams_id = $exam_id;
        $question->lessons_id = $request->input('lessons_id');
        $question->factor = ($request->input('factor'))?$request->input('factor'):1;
        $question->description = $request->input('description');
        $question->order = ($request->input('order'))?$request->input('order'):0;
        $question->save();

        $request->session()->flash("msg_success", "سوال با موفقیت افزوده شد.");
        return redirect()->route('exam_questions', ["exam_id"=>$exam_id]);
    }
}
