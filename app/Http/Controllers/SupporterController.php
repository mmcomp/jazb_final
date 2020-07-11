<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Group;
use App\Student;
use App\User;
use App\Source;
use App\Product;
use App\Tag;
use App\Temperature;
use App\Call;
use App\CallResult;
use App\StudentCollection;
use App\Collection;
use App\Purchase;

class SupporterController extends Controller
{
    public function index(){
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->with('students.purchases')->with('students.studenttags.tag')->orderBy('max_student', 'desc')->get();
        // dd($supporters);
        return view('supporters.index',[
            'supporters' => $supporters,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function student(){
        $students = Student::where('is_deleted', false)->where('supporters_id', Auth::user()->id);
        $sources = Source::where('is_deleted', false)->get();
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        $callResults = CallResult::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        $has_collection = 'false';
        $has_the_product = '';
        $has_site = 'false';
        $order_collection = 'false';
        $has_reminder = 'false';
        $has_tag = 'false';
        if(request()->getMethod()=='POST'){
            // dump(request()->all());
            if(request()->input('name')!=null){
                $name = trim(request()->input('name'));
                $students = $students->where(function ($query) use ($name) {
                    $query->where('first_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }
            if(request()->input('sources_id')!=null){
                $sources_id = (int)request()->input('sources_id');
                $students = $students->where('sources_id', $sources_id);
            }
            if(request()->input('phone')!=null){
                $phone = (int)request()->input('phone');
                $students = $students->where('phone', $phone);
            }
            if(request()->input('has_collection')!=null){
                $has_collection = request()->input('has_collection');
                if($has_collection=='true'){
                    $studentCollections = StudentCollection::where('is_deleted', false)->pluck('students_id');
                    $students = $students->whereIn('id', $studentCollections);
                }
            }
            if(request()->input('has_the_product')!=null && request()->input('has_the_product')!=''){
                $has_the_product = request()->input('has_the_product');
                $purchases = Purchase::where('is_deleted', false)->where('type', '!=', 'site_failed')->where('products_id', $has_the_product)->pluck('students_id');
                $students = $students->whereIn('id', $purchases);
            }
            if(request()->input('has_site')!=null){
                $has_site = request()->input('has_site');
                if($has_site=='true'){
                    $purchases = Purchase::where('is_deleted', false)->where('type', 'site_successed')->pluck('students_id');
                    $students = $students->whereIn('id', $purchases);
                }
            }
            if(request()->input('has_reminder')!=null){
                $has_reminder = request()->input('has_reminder');
                if($has_reminder=='true'){
                    $students = $students->has('remindercalls');
                }
            }
            if(request()->input('has_tag')!=null){
                $has_tag = request()->input('has_tag');
                if($has_tag=='true'){
                    $students = $students->has('studenttags');
                }
            }
        }

        $students = $students
        ->with('user')
        ->with('studentcollections.collection')
        ->with('studenttags.tag')
        ->with('studenttemperatures.temperature')
        ->with('source')
        ->with('consultant')
        ->with('calls.product')
        ->with('calls.callresult')
        ->orderBy('created_at', 'desc')
        ->get();

        if(request()->input('order_collection')!=null){
            $order_collection = request()->input('order_collection');
            if($order_collection=='true'){
                $students = $students->sortBy(function($hackathon)
                {
                    return $hackathon->studentcollections->count();
                }, SORT_REGULAR, true);
            }
        }

        $moralTags = Tag::where('is_deleted', false)->where('type', 'moral')->get();
        // $needTags = Tag::where('is_deleted', false)->where('type', 'need')->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();
        $collections = Collection::where('is_deleted', false)->get();

        return view('supporters.student',[
            'students' => $students,
            'sources' => $sources,
            'name' => $name,
            'sources_id' => $sources_id,
            'phone' => $phone,
            'moralTags'=>$moralTags,
            'needTags'=>$collections,
            'hotTemperatures'=>$hotTemperatures,
            'coldTemperatures'=>$coldTemperatures,
            'products'=>$products,
            'callResults'=>$callResults,
            'has_collection'=>$has_collection,
            'has_the_product'=>$has_the_product,
            'has_site'=>$has_site,
            'order_collection'=>$order_collection,
            'has_reminder'=>$has_reminder,
            'has_tag'=>$has_tag,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
        //---------------------AJAX-----------------------------------
        public function call(Request $request){
            $students_id = $request->input('students_id');

            $student = Student::where('id', $students_id)->where('is_deleted', false)->first();
            if($student==null){
                return [
                    "error"=>"student_not_found",
                    "data"=>null
                ];
            }

            $call = new Call;
            $call->title = 'تماس';
            $call->students_id = $students_id;
            $call->users_id = Auth::user()->id;
            $call->description = $request->input('description');
            $call->call_results_id = $request->input('call_results_id');
            $call->replier = $request->input('replier');
            $call->products_id = $request->input('products_id');
            $call->next_to_call = $request->input('next_to_call');
            $call->next_call = $request->input('next_call');
            $call->save();

            return [
                "error"=>null,
                "data"=>null
            ];
        }
}
