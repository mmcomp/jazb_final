<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Morilog\Jalali\CalendarUtils;

use App\Group;
use App\Student;
use App\User;
use App\Source;
use App\Product;
use App\Tag;
use App\TagParentOne;
use App\TagParentTwo;
use App\TagParentThree;
use App\TagParentFour;
use App\Temperature;
use App\Call;
use App\CallResult;
use App\StudentCollection;
use App\Collection;
use App\Notice;
use App\Purchase;
use App\StudentTag;
use App\City;
use Exception;

class SupporterController extends Controller
{
    public static function persianToEnglishDigits($pnumber) {
        $number = str_replace('۰', '0', $pnumber);
        $number = str_replace('۱', '1', $number);
        $number = str_replace('۲', '2', $number);
        $number = str_replace('۳', '3', $number);
        $number = str_replace('۴', '4', $number);
        $number = str_replace('۵', '5', $number);
        $number = str_replace('۶', '6', $number);
        $number = str_replace('۷', '7', $number);
        $number = str_replace('۸', '8', $number);
        $number = str_replace('۹', '9', $number);
        return $number;
    }

    public static function jalaliToGregorian($pdate){
		$pdate = explode('/', SupporterController::persianToEnglishDigits($pdate));
		$date = "";
		if(count($pdate)==3){
			$y = (int)$pdate[0];
			$m = (int)$pdate[1];
			$d = (int)$pdate[2];
			if($d > $y)
			{
				$tmp = $d;
				$d = $y;
				$y = $tmp;
			}
			$y = (($y<1000)?$y+1300:$y);
			$gregorian = CalendarUtils::toGregorian($y,$m,$d);
			$gregorian = $gregorian[0]."-".$gregorian[1]."-".$gregorian[2];
		}
		return $gregorian;
	}
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

    public function callIndex(){
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supporters_id = null;
        $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId);
        if(request()->getMethod()=='POST'){
            if(request()->input('supporters_id')){
                $supporters_id = request()->input('supporters_id');
                $supporters = $supporters->where('id', $supporters_id);
            }
        }
        $supporters = $supporters->with('students.purchases')->with('students.studenttags.tag')->orderBy('max_student', 'desc')->get();

        $callResults = CallResult::where('is_deleted', false)->get();

        $from_date = null;
        $to_date = null;
        $products_id = null;
        $notices_id = null;
        $replier_id = null;
        $sources_id = null;

        foreach($supporters as $index=>$supporter) {
            $call = Call::where("users_id", $supporter->id);
            $supporterCallResults = $callResults->ToArray();
            foreach($supporterCallResults as $sindex => $supporterCallResult){
                $supporterCallResults[$sindex]['count'] = 0;
            }


            if(request()->getMethod()=='POST'){
                if(request()->input('from_date')){
                    $from_date = SupporterController::jalaliToGregorian(request()->input('from_date'));
                    if($from_date != '')
                        $call->where('created_at', '>=', $from_date);
                }
                if(request()->input('to_date')){
                    $to_date = SupporterController::jalaliToGregorian(request()->input('to_date'));
                    if($to_date != '')
                        $call->where('created_at', '<=', $to_date);
                }
                if(request()->input('products_id')){
                    $products_id = (int)request()->input('products_id');
                    if($products_id > 0)
                        $call->where('products_id', $products_id);
                }
                if(request()->input('notices_id')){
                    $notices_id = (int)request()->input('notices_id');
                    if($notices_id > 0)
                        $call->where('notices_id', $notices_id);
                }
                if(request()->input('sources_id')){
                    $sources_id = (int)request()->input('sources_id');
                    if($sources_id > 0){
                        $students = Student::where('sources_id', $sources_id)->where('is_deleted', false)->where('banned', false)->pluck('id');
                        $call->whereIn('students_id', $students);
                    }
                }
            }
            if($from_date == null && $to_date == null) {
                $call->where('created_at', '<=', date("Y-m-d 23:59:59"))->where('created_at', '>=', date("Y-m-d 00:00:00"));
            }
            $calls = $call->get();
            foreach($calls as $theCall){
                foreach($supporterCallResults as $sindex => $supporterCallResult){
                    if($supporterCallResult['id'] == $theCall->call_results_id){
                        $supporterCallResults[$sindex]['count']++;
                    }
                }
            }
            $supporters[$index]->callCount = count($calls);
            $supporters[$index]->supporterCallResults = $supporterCallResults;
        }
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        $notices = Notice::where('is_deleted', false)->get();
        $sources = Source::where('is_deleted', false)->get();
        // dd($supporters);
        return view('supporters.calls',[
            'supporters' => $supporters,
            'products' => $products,
            'notices' => $notices,
            'sources' => $sources,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'products_id' => $products_id,
            'notices_id' => $notices_id,
            'supporters_id' => $supporters_id,
            'replier_id' => $replier_id,
            'sources_id' => $sources_id,
            'callResults' => $callResults,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function acallIndex(Request $request) {
        $supporter = User::find($request->input("id"));
        if(request()->input('call_id')) {
            $call =  Call::where("users_id", $supporter->id)->where('id', request()->input('call_id'))->first();
            if($call) {
                $call->delete();
            }
        }
        $calls = Call::where("users_id", $supporter->id);
        if(request()->input('from_date')){
            $from_date = request()->input('from_date');
            if($from_date != '')
                $calls->where('created_at', '>=', $from_date);
        }
        if(request()->input('to_date')){
            $to_date = request()->input('to_date');
            if($to_date != '')
                $calls->where('created_at', '<=', $to_date);
        }
        if(request()->input('products_id')){
            $products_id = (int)request()->input('products_id');
            if($products_id > 0)
                $calls->where('products_id', $products_id);
        }
        if(request()->input('notices_id')){
            $notices_id = (int)request()->input('notices_id');
            if($notices_id > 0)
                $calls->where('notices_id', $notices_id);
        }
        if(request()->input('sources_id')){
            $sources_id = (int)request()->input('sources_id');
            if($sources_id > 0){
                $students = Student::where('sources_id', $sources_id)->where('is_deleted', false)->where('banned', false)->pluck('id');
                $calls->whereIn('students_id', $students);
            }
        }
        $calls = $calls->with('student')->with('product.collection')->with('notice')->get();
        foreach($calls as $index=>$call) {
            if($call->product) {
                $product = $call->product;
                $product->parents = "-";
                if($product->collection) {
                    $parents = $product->collection->parents();
                    $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                    $product->parents = $name;
                }
                $calls[$index]->product = $product;
            }
        }
        // dd($calls);
        return view("supporters.supportercalls", [
            "supporter"=>$supporter,
            "calls"=>$calls,
            "request"=>request()->all()
        ]);
    }

    public function create(Request $request)
    {
        $groups = Group::all();
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $user = new User;
        if($request->getMethod()=='GET'){
            return view('supporters.create', [
                "groups"=>$groups,
                "user"=>$user
            ]);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->groups_id = $supportGroupId;//(int)$request->input('groups_id');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->pass = $request->input('password');
        $user->gender = $request->input('gender');
        $user->national_code = $request->input('national_code');
        $user->education = $request->input('education');
        $user->major = $request->input('major');
        $user->home_phone = $request->input('home_phone');
        $user->mobile = $request->input('mobile');
        $user->work_mobile = $request->input('work_mobile');
        $user->home_address = $request->input('home_address');
        $user->work_address = $request->input('work_address');
        $user->max_student = (int)$request->input('max_student');
        if($request->file('image_path')){
            $filename = now()->timestamp . '.' . $request->file('image_path')->extension();
            $user->image_path = $request->file('image_path')->storeAs('supporters', $filename, 'public_uploads');
        }

        $find = User::where('email', $request->input('email'))->first();
        if($find){
            $request->session()->flash("msg_error", "نام کاربری قبلا استفاده شده است!");
            return view('supporters.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }


        if($request->input('password')!=$request->input('repassword')){
            $request->session()->flash("msg_error", "رمز عبور و تکرار آن باید یکی باشند");
            return view('supporters.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }

        $user->save();

        $request->session()->flash("msg_success", "کاربر با موفقیت افزوده شد.");
        return redirect()->route('user_supporters');
    }

    public function students($id){
        return $this->student($id);
    }

    public function student($id = null){
        // dump(request()->all());
        $user = null;
        if($id==null){
            $id = Auth::user()->id;
        }else {
            $user = User::find($id);
        }
        // Student::where('is_deleted', false)->where('supporters_id', $id)->where('viewed', false)->update([
        //     'viewed'=>true
        // ]);
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', $id);
        $sources = Source::where('is_deleted', false)->get();
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        // $callResults = CallResult::where('is_deleted', false)->get();
        foreach($products as $index => $product){
            $products[$index]->parents = "-";
            if($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents!='')?$parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        $callResults = CallResult::where('is_deleted', false)->get();
        $notices = Notice::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        $has_collection = 'false';
        $has_the_product = '';
        $has_the_tags = '';
        $has_call_result = '';
        $has_site = 'false';
        $order_collection = 'false';
        $has_reminder = 'false';
        $has_tag = 'false';
        if(request()->getMethod()=='POST'){
            // dump(request()->all());
            if(request()->input('name')!=null){
                $name = trim(request()->input('name'));
                $students = $students->where(function ($query) use ($name) {
                    $tmpNames = explode(' ', $name);
                    foreach($tmpNames as $tmpName) {
                        $tmpName = trim($tmpName);
                        $query->orWhere('first_name', 'like', '%' . $tmpName . '%')->orWhere('last_name', 'like', '%' . $tmpName . '%');
                    }
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
                $purchases = Purchase::where('is_deleted', false)->where('type', '!=', 'site_failed')->whereIn('products_id', explode(',', $has_the_product))->pluck('students_id');
                $students = $students->whereIn('id', $purchases);
            }
            if(request()->input('has_call_result')!=null && request()->input('has_call_result')!=''){
                $has_call_result = request()->input('has_call_result');
                $calls = Call::where('call_results_id', $has_call_result);
                if($has_the_product!='') {
                    $calls = $calls->whereIn('products_id', explode(',', $has_the_product));
                }
                $calls = $calls->pluck('students_id');
                $students = $students->whereIn('id', $calls);
            }
            if(request()->input('has_the_tags')!=null && request()->input('has_the_tags')!=''){
                $has_the_tags = request()->input('has_the_tags');
                $studentTags = StudentTag::where('is_deleted', false)->whereIn('tags_id', explode(',', $has_the_tags))->pluck('students_id');
                $students = $students->whereIn('id', $studentTags);
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
        ->with('studenttags.tag.parent_four')
        ->with('studenttemperatures.temperature')
        ->with('source')
        ->with('consultant')
        ->with('calls.product')
        ->with('calls.notice')
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
        $parentOnes = TagParentOne::has('tags')->where('is_deleted', false)->get();
        $parentTwos = TagParentTwo::has('tags')->where('is_deleted', false)->get();
        $parentThrees = TagParentThree::has('tags')->where('is_deleted', false)->get();
        $parentFours = TagParentFour::has('tags')->where('is_deleted', false)->get();
        $collections = Collection::where('is_deleted', false)->get();
        $firstCollections = Collection::where('is_deleted', false)->where('parent_id', 0)->get();
        $secondCollections = Collection::where('is_deleted', false)->whereIn('parent_id', $firstCollections->pluck('id'))->get();
        $thirdCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $secondCollections->pluck('id'))->get();

        $moralTags = Tag::where('is_deleted', false)->where('type', 'moral')->get();
        // $needTags = Tag::where('is_deleted', false)->where('type', 'need')->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();
        $collections = Collection::where('is_deleted', false)->get();

        foreach($students as $index => $student) {
            $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
            if($students[$index]->calls)
                foreach($students[$index]->calls as $cindex => $call) {
                    $students[$index]->calls[$cindex]->next_call = ($students[$index]->calls[$cindex]->next_call) ? jdate(strtotime($students[$index]->calls[$cindex]->next_call))->format("Y/m/d"):null;
                }
        }

        if(request()->getMethod()=='GET'){
            // dd($has_the_product);
            return view('supporters.student',[
                'user'=>$user,
                'students' => $students,
                'sources' => $sources,
                'name' => $name,
                'sources_id' => $sources_id,
                'phone' => $phone,
                'moralTags'=>$moralTags,
                'needTags'=>$collections,
                'hotTemperatures'=>$hotTemperatures,
                'coldTemperatures'=>$coldTemperatures,
                "parentOnes"=>$parentOnes,
                "parentTwos"=>$parentTwos,
                "parentThrees"=>$parentThrees,
                "parentFours"=>$parentFours,
                "firstCollections"=>$firstCollections,
                "secondCollections"=>$secondCollections,
                "thirdCollections"=>$thirdCollections,
                'products'=>$products,
                'notices'=>$notices,
                'callResults'=>$callResults,
                'has_collection'=>$has_collection,
                'has_the_product'=>($has_the_product!='')?explode(',', $has_the_product):'',
                'has_the_tags'=>($has_the_tags!='')?explode(',', $has_the_tags):'',
                'has_call_result'=>$has_call_result,
                'has_site'=>$has_site,
                'order_collection'=>$order_collection,
                'has_reminder'=>$has_reminder,
                'has_tag'=>$has_tag,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else {
            $req =  request()->all();
            // dd($req);
            if(!isset($req['start'])){
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach($students as $index => $item){
                $tags = "";
                if(($item->studenttags && count($item->studenttags)>0) || ($item->studentcollections && count($item->studentcollections)>0)){
                    for($i = 0; $i < count($item->studenttags);$i++){
                        $tags .= '<span class="alert alert-info p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '' ) . ' ' . $item->studenttags[$i]->tag->name . '
                        </span><br/>';
                    }
                    for($i = 0; $i < count($item->studentcollections);$i++){
                        if(isset($item->studentcollections[$i]->collection))
                            $tags .= '<span class="alert alert-warning p-1">
                                '. (($item->studentcollections[$i]->collection->parent) ? $item->studentcollections[$i]->collection->parent->name . '->' : '' ) . ' ' . $item->studentcollections[$i]->collection->name .'
                            </span><br/>';
                    }
                }
                $registerer = "-";
                if($item->user)
                    $registerer =  $item->user->first_name . ' ' . $item->user->last_name;
                elseif($item->saloon)
                    $registerer = $item->saloon;
                elseif($item->is_from_site)
                    $registerer =  'سایت';

                $temps = "";
                if($item->studenttemperatures && count($item->studenttemperatures)>0) {
                    foreach ($item->studenttemperatures as $sitem){
                        if($sitem->temperature->status=='hot')
                            $temps .= '<span class="alert alert-danger p-1">';
                        else
                            $temps .= '<span class="alert alert-info p-1">';
                        $temps .= $sitem->temperature->name . '</span>';
                    }
                }
                $data[] = [
                    $index+1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source)?$item->source->name:'-',
                    $tags,
                    $temps,
                    ""
                ];
            }

            $outdata = [];
            for($i = $req['start'];$i<min($req['length']+$req['start'], count($data));$i++){
                $outdata[] = $data[$i];
            }

            $result = [
                "draw" => $req['draw'],
                "data" => $outdata,
                "recordsTotal" => count($students),
                "recordsFiltered" => count($students)
            ];

            return $result;
        }
    }

    public function newStudents(){
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporter_seen', false)->where('supporters_id', Auth::user()->id);
        $sources = Source::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
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
        }
        // DB::enableQueryLog();
        $students = $students
            ->with('user')
            ->with('studenttags.tag.parent_four')
            ->with('studentcollections.collection')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->with('consultant')
            ->with('supporter')
            ->orderBy('created_at', 'desc')
            ->get();
        // dd(DB::getQueryLog());
        $moralTags = Tag::where('is_deleted', false)
            // ->with('parent_one')
            // ->with('parent_two')
            // ->with('parent_three')
            // ->with('parent_four')
            ->where('type', 'moral')
            ->get();
        $parentOnes = TagParentOne::has('tags')->get();
        $parentTwos = TagParentTwo::has('tags')->get();
        $parentThrees = TagParentThree::has('tags')->get();
        $parentFours = TagParentFour::has('tags')->get();
        $collections = Collection::where('is_deleted', false)->get();
        $firstCollections = Collection::where('is_deleted', false)->where('parent_id', 0)->get();
        $secondCollections = Collection::where('is_deleted', false)->whereIn('parent_id', $firstCollections->pluck('id'))->get();
        $thirdCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $secondCollections->pluck('id'))->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();

        foreach($students as $index => $student) {
            $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
        }

        if(request()->getMethod()=='GET'){
            return view('supporters.new',[
                'students' => $students,
                'sources' => $sources,
                'name' => $name,
                'sources_id' => $sources_id,
                'phone' => $phone,
                'moralTags'=>$moralTags,
                'needTags'=>$collections,
                'hotTemperatures'=>$hotTemperatures,
                'coldTemperatures'=>$coldTemperatures,
                "parentOnes"=>$parentOnes,
                "parentTwos"=>$parentTwos,
                "parentThrees"=>$parentThrees,
                "parentFours"=>$parentFours,
                "firstCollections"=>$firstCollections,
                "secondCollections"=>$secondCollections,
                "thirdCollections"=>$thirdCollections,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else{
            $req =  request()->all();
            // dd($req);
            if(!isset($req['start'])){
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach($students as $index => $item){
                $tags = "";
                if(($item->studenttags && count($item->studenttags)>0) || ($item->studentcollections && count($item->studentcollections)>0)){
                    for($i = 0; $i < count($item->studenttags);$i++){
                        $tags .= '<span class="alert alert-info p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '' ) . ' ' . $item->studenttags[$i]->tag->name . '
                    </span><br/>';
                    }
                    for($i = 0; $i < count($item->studentcollections);$i++){
                        $tags .= '<span class="alert alert-warning p-1">
                            '. (($item->studentcollections[$i]->collection->parent) ? $item->studentcollections[$i]->collection->parent->name . '->' : '' ) . ' ' . $item->studentcollections[$i]->collection->name .'
                        </span><br/>';
                    }
                }
                $registerer = "-";
                if($item->user)
                    $registerer =  $item->user->first_name . ' ' . $item->user->last_name;
                elseif($item->saloon)
                    $registerer = $item->saloon;
                elseif($item->is_from_site)
                    $registerer =  'سایت';

                $temps = "";
                if($item->studenttemperatures && count($item->studenttemperatures)>0) {
                    foreach ($item->studenttemperatures as $sitem){
                        if($sitem->temperature->status=='hot')
                            $temps .= '<span class="alert alert-danger p-1">';
                        else
                            $temps .= '<span class="alert alert-info p-1">';
                        $temps .= $sitem->temperature->name . '</span>';
                    }
                }
                $data[] = [
                    $index+1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source)?$item->source->name:'-',
                    $tags,
                    $temps,
                    $item->description,
                    '<a class="btn btn-success btn-sm" href="#" onclick="return seeStudent(this, ' . $item->id . ');">
                    مشاهده شد
                    </a>'
                ];
            }

            $outdata = [];
            for($i = $req['start'];$i<min($req['length']+$req['start'], count($data));$i++){
                $outdata[] = $data[$i];
            }

            $result = [
                "draw" => $req['draw'],
                "data" => $outdata,
                "recordsTotal" => count($students),
                "recordsFiltered" => count($students)
            ];

            return $result;
        }
    }

    public function purchases(){
        $students = Student::where('is_deleted', false)->where('banned', false)->where('supporter_seen', false)->where('supporters_id', Auth::user()->id);
        $sources = Source::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
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
        }
        // DB::enableQueryLog();
        $students = $students
            ->with('user')
            ->with('studenttags.tag')
            ->with('studentcollections.collection')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->with('consultant')
            ->with('supporter')
            ->orderBy('created_at', 'desc')
            ->get();
        // dd(DB::getQueryLog());
        $moralTags = Tag::where('is_deleted', false)
            // ->with('parent_one')
            // ->with('parent_two')
            // ->with('parent_three')
            // ->with('parent_four')
            ->where('type', 'moral')
            ->get();
        $parentOnes = TagParentOne::has('tags')->get();
        $parentTwos = TagParentTwo::has('tags')->get();
        $parentThrees = TagParentThree::has('tags')->get();
        $parentFours = TagParentFour::has('tags')->get();
        $collections = Collection::where('is_deleted', false)->get();
        $firstCollections = Collection::where('is_deleted', false)->where('parent_id', 0)->get();
        $secondCollections = Collection::where('is_deleted', false)->whereIn('parent_id', $firstCollections->pluck('id'))->get();
        $thirdCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $secondCollections->pluck('id'))->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();

        $finalStudents = [];
        foreach($students as $student) {
            $student->ownPurchases = $student->purchases()->where('supporters_id', Auth::user()->id)->get();
            $student->otherPurchases = $student->purchases()->where('supporters_id', '!=', Auth::user()->id)->get();
            $student->todayPurchases = $student->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->get();
            $finalStudents[] = $student;
        }

        return view('supporters.purchase',[
            'students' => $finalStudents,
            'sources' => $sources,
            'name' => $name,
            'sources_id' => $sources_id,
            'phone' => $phone,
            'moralTags'=>$moralTags,
            'needTags'=>$collections,
            'hotTemperatures'=>$hotTemperatures,
            'coldTemperatures'=>$coldTemperatures,
            "parentOnes"=>$parentOnes,
            "parentTwos"=>$parentTwos,
            "parentThrees"=>$parentThrees,
            "parentFours"=>$parentFours,
            "firstCollections"=>$firstCollections,
            "secondCollections"=>$secondCollections,
            "thirdCollections"=>$thirdCollections,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function deleteCall($users_id, $id)
    {
        $call = Call::find($id);
        if($call)
            $call->delete();
        return redirect()->route('supporter_student_allcall', ["id"=>$users_id]);
    }

    public function calls($id) {
        $student = Student::where('id', $id)->where('banned', false)->with('calls.product')->with('calls.product.collection')->with('calls.callresult')->first();
        if($student->calls)
            foreach($student->calls as $index=>$call){
                if($student->calls[$index]->product){
                    $student->calls[$index]->product->parents = "-";
                    if($student->calls[$index]->product->collection) {
                        $parents = $student->calls[$index]->product->collection->parents();
                        $name = ($parents!='')?$parents . "->" . $student->calls[$index]->product->collection->name : $student->calls[$index]->product->collection->name;
                        $student->calls[$index]->product->parents = $name;
                    }
                }
            }
        return view('supporters.call',[
            "student"=>$student
        ]);
    }

    public function studentCreate() {
        $student = new Student();
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $consultantGroupId = Group::getConsultant();
        if($consultantGroupId)
            $consultantGroupId = $consultantGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $consultants = User::where('is_deleted', false)->where('groups_id', $consultantGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        $cities = City::where('is_deleted', false)->get();
        if(request()->getMethod()=='GET'){
            return view('students.create', [
                "supports"=>$supports,
                "cities"=>$cities,
                "consultants"=>$consultants,
                "sources"=>$sources,
                "student"=>$student
            ]);
        }

        $student->users_id = Auth::user()->id;
        $student->first_name = request()->input('first_name');
        $student->last_name = request()->input('last_name');
        $student->last_year_grade = (int)request()->input('last_year_grade');
        $student->consultants_id = request()->input('consultants_id');
        $student->parents_job_title = request()->input('parents_job_title');
        $student->home_phone = request()->input('home_phone');
        $student->egucation_level = request()->input('egucation_level');
        $student->father_phone = request()->input('father_phone');
        $student->mother_phone = request()->input('mother_phone');
        $student->phone  = request()->input('phone');
        $student->school = request()->input('school');
        $student->average = request()->input('average');
        $student->major = request()->input('major');
        $student->introducing = request()->input('introducing');
        $student->student_phone = request()->input('student_phone');
        $student->sources_id = request()->input('sources_id');
        $student->supporters_id = $student->users_id;
        $student->supporter_seen = false;
        try{
            $student->save();
        }catch(Exception $e){
            // dd($e);
            $msg = "خطا در ثبت رخ داد است";
            if($e->getCode() == 23000)
                $msg = "شماره تلفن تکراری است!";

            request()->session()->flash("msg_error", $msg);
            return redirect()->route('supporter_student_new');
        }

        request()->session()->flash("msg_success", "دانش آموز با موفقیت افزوده شد.");
        return redirect()->route('supporter_student_new');
    }
    //---------------------AJAX-----------------------------------
    public function call(Request $request){
        $students_id = $request->input('students_id');

        $student = Student::where('id', $students_id)->where('banned', false)->where('is_deleted', false)->first();
        if($student==null){
            return [
                "error"=>"student_not_found",
                "data"=>null
            ];
        }

        if($request->input('products_id')==null){
            $call = new Call;
            $call->title = 'تماس';
            $call->students_id = $students_id;
            $call->users_id = Auth::user()->id;
            $call->description = $request->input('description');
            $call->call_results_id = $request->input('call_results_id');
            $call->replier = $request->input('replier');
            $call->next_to_call = $request->input('next_to_call');
            $call->next_call = $request->input('next_call');
            $call->notices_id = ($request->input('notices_id')==null)?0:$request->input('notices_id');
            $call->products_id = 0;
            try{
                $call->save();
            }catch(Exception $e){
            }
        }else {
            foreach($request->input('products_id') as $products_id ){
                $call = new Call;
                $call->title = 'تماس';
                $call->students_id = $students_id;
                $call->users_id = Auth::user()->id;
                $call->description = $request->input('description');
                $call->call_results_id = $request->input('call_results_id');
                $call->replier = $request->input('replier');
                $call->products_id = $products_id;
                $call->next_to_call = $request->input('next_to_call');
                $call->next_call = $request->input('next_call');
                $call->notices_id = ($request->input('notices_id')==null)?0:$request->input('notices_id');
                try{
                    $call->save();
                }catch(Exception $e){
                }
            }
        }

        return [
            "error"=>null,
            "data"=>null
        ];
    }

    public function changePass(Request $request){
        if(!Gate::allows('parameters')){
            return [
                "error"=>"permission denied",
                "data"=>null
            ];
        }

        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->where('is_deleted', false)->first();
        if($user==null){
            return [
                "error"=>"user_not_found",
                "data"=>null
            ];
        }

        $user->password = Hash::make($request->input('password'));
        $user->pass = $request->input('password');
        $user->save();

        return [
            "error"=>null,
            "data"=>null
        ];
    }

    public function seen(Request $request){
        if($request->input('student_id')==null){
            return [
                "error"=>"InvalidInput",
                "data"=>null
            ];
        }

        $student = Student::find($request->input('student_id'));
        if($student==null){
            return [
                "error"=>"StudentNotFound",
                "data"=>null
            ];
        }

        $student->supporter_seen = true;
        $student->save();
        return [
            "error"=>null,
            "data"=>$student
        ];
    }
}
