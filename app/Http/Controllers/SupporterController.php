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
use App\NeedTagParentOne;
use App\NeedTagParentTwo;
use App\NeedTagParentThree;
use App\NeedTagParentFour;
use App\Temperature;
use App\Call;
use App\CallResult;
use App\StudentCollection;
use App\Collection;
use App\Notice;
use App\Purchase;
use App\StudentTag;
use App\City;
use App\MergeStudents as AppMergeStudents;
use App\SaleSuggestion;
use Exception;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupporterController extends Controller
{
    public function allMissedCalls(){
        $all_missed_calls = Call::where('users_id',Auth::user()->id)->where('call_results_id',1)->get();
        return view('supporters.allMissedCalls')->with([
            'all_missed_calls' => $all_missed_calls
        ]);
    }
    public function yesterdayMissedCalls(){
        $missed_calls_of_yesterday = Call::where('users_id',Auth::user()->id)->where('call_results_id',1)->where('created_at','>=',Carbon::yesterday())->get();

        return view('supporters.yesterdayMissedCalls')->with([
            'yesterday_missed_calls' => $missed_calls_of_yesterday
        ]);
    }
    public static function persianToEnglishDigits($pnumber)
    {
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

    public static function jalaliToGregorian($pdate)
    {
        $pdate = explode('/', SupporterController::persianToEnglishDigits($pdate));
        $date = "";
        if (count($pdate) == 3) {
            $y = (int)$pdate[0];
            $m = (int)$pdate[1];
            $d = (int)$pdate[2];
            if ($d > $y) {
                $tmp = $d;
                $d = $y;
                $y = $tmp;
            }
            $y = (($y < 1000) ? $y + 1300 : $y);
            $gregorian = CalendarUtils::toGregorian($y, $m, $d);
            $gregorian = $gregorian[0] . "-" . $gregorian[1] . "-" . $gregorian[2];
        }
        return $gregorian;
    }
    public function index()
    {
        $supportGroupId = Group::getSupport();
        if ($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->with('students.purchases')->with('students.studenttags.tag')->orderBy('max_student', 'desc')->get();
        // dd($supporters);
        return view('supporters.index', [
            'supporters' => $supporters,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }

    public function supporterCallIndex()
    {
        $theSupporters_id = Auth::user()->id;
        return $this->callIndex($theSupporters_id);
    }

    public function callIndex($theSupporters_id = null)
    {
        $supporters_id = null;
        $supportersForSelectInView = null;
        if ($theSupporters_id == null) {
            $supportGroupId = Group::getSupport();
            if ($supportGroupId)
                $supportGroupId = $supportGroupId->id;
            $supporters_id = null;
            $supporters = User::where('is_deleted', false)->where('groups_id', $supportGroupId);
            $supportersForSelectInView = $supporters->get();
            if (request()->getMethod() == 'POST') {
                if (request()->input('supporters_id')) {
                    $supporters_id = request()->input('supporters_id');
                    $supporters = $supporters->where('id', $supporters_id);
                }
            }
            $supporters = $supporters->with('students.purchases')->with('students.studenttags.tag')->orderBy('max_student', 'desc')->get();
        } else {
            $supporters = User::where('id', $theSupporters_id)->get();
            $supportersForSelectInView = User::where('is_deleted',false)->get();
        }

        $callResults = CallResult::where('is_deleted', false)->get();

        $from_date = null;
        $to_date = null;
        $products_id = null;
        $notices_id = null;
        $replier_id = null;
        $sources_id = null;

        foreach ($supporters as $index => $supporter) {
            $call = Call::where("users_id", $supporter->id);
            $supporterCallResults = $callResults->ToArray();
            foreach ($supporterCallResults as $sindex => $supporterCallResult) {
                $supporterCallResults[$sindex]['count'] = 0;
            }


            if (request()->getMethod() == 'POST') {
                if (request()->input('from_date')) {
                    $from_date = SupporterController::jalaliToGregorian(request()->input('from_date'));
                    if ($from_date != '')
                        $call->where('created_at', '>=', $from_date);
                }
                if (request()->input('to_date')) {
                    $to_date = SupporterController::jalaliToGregorian(request()->input('to_date'));
                    if ($to_date != '')
                        $call->where('created_at', '<=', $to_date);
                }
                if (request()->input('products_id')) {
                    $products_id = (int)request()->input('products_id');
                    if ($products_id > 0)
                        $call->where('products_id', $products_id);
                }
                if (request()->input('notices_id')) {
                    $notices_id = (int)request()->input('notices_id');
                    if ($notices_id > 0)
                        $call->where('notices_id', $notices_id);
                }
                if (request()->input('sources_id')) {
                    $sources_id = (int)request()->input('sources_id');
                    if ($sources_id > 0) {
                        $students = Student::where('sources_id', $sources_id)->where('is_deleted', false)->where('banned', false)->pluck('id');
                        $call->whereIn('students_id', $students);
                    }
                }
            }
            if ($from_date == null && $to_date == null) {
                $call->where('created_at', '<=', date("Y-m-d 23:59:59"))->where('created_at', '>=', date("Y-m-d 00:00:00"));
            }
            $calls = $call->get();
            foreach ($calls as $theCall) {
                foreach ($supporterCallResults as $sindex => $supporterCallResult) {
                    if ($supporterCallResult['id'] == $theCall->call_results_id) {
                        $supporterCallResults[$sindex]['count']++;
                    }
                }
            }
            $supporters[$index]->callCount = count($calls);
            $supporters[$index]->supporterCallResults = $supporterCallResults;
        }
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach ($products as $index => $product) {
            $products[$index]->parents = "-";
            if ($product->collection) {
                $parents = $product->collection->parents();
                $name = ($parents != '') ? $parents . "->" . $product->collection->name : $product->collection->name;
                $products[$index]->parents = $name;
            }
        }
        $notices = Notice::where('is_deleted', false)->get();
        $sources = Source::where('is_deleted', false)->get();
        return view('supporters.calls', [
            'supportersForSelectInView' => $supportersForSelectInView,
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
            "isSingle" => ($theSupporters_id != null),
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }


    public function acallIndex(Request $request, $id)
    {
        $supporter = User::find($id);
        $persons = [
            "student" => "دانش آموز",
            "father" => "پدر",
            "mother" => "مادر",
            "other" => "غیره"
        ];
        $fullName = null;
        if ($request->getMethod() == 'POST') {
            if (request()->input('call_id')) {
                $call =  Call::where("users_id", $id)->where('id', request()->input('call_id'))->first();
                if ($call) {
                    $call->delete();
                }
            }
            $calls = Call::where("users_id", $id);
            if (request()->input('from_date')) {
                $from_date = request()->input('from_date');
                if ($from_date != '')
                    $calls->where('created_at', '>=', $from_date);
            }
            if (request()->input('to_date')) {
                $to_date = request()->input('to_date');
                if ($to_date != '')
                    $calls->where('created_at', '<=', $to_date);
            }
            if (request()->input('products_id')) {
                $products_id = (int)request()->input('products_id');
                if ($products_id > 0)
                    $calls->where('products_id', $products_id);
            }
            if (request()->input('notices_id')) {
                $notices_id = (int)request()->input('notices_id');
                if ($notices_id > 0)
                    $calls->where('notices_id', $notices_id);
            }
            if (request()->input('sources_id')) {
                $sources_id = (int)request()->input('sources_id');
                if ($sources_id > 0) {
                    $students = Student::where('sources_id', $sources_id)->where('is_deleted', false)->where('banned', false)->pluck('id');
                    $calls->whereIn('students_id', $students);
                }
            }
            if (request()->input('fullName')) {
                $fullName = trim(request()->input('fullName'));
                $students = Student::select('id', DB::raw("CONCAT(first_name,' ',last_name)"))->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $fullName . '%')
                    ->where('is_deleted', false)->where('banned', false)->pluck('id');
                $calls->whereIn('students_id', $students);
            }
            $calls = $calls->with('student')->with('product.collection')->with('notice')->get();
            foreach ($calls as $index => $call) {
                if ($call->product) {
                    $product = $call->product;
                    $product->parents = "-";
                    if ($product->collection) {
                        $parents = $product->collection->parents();
                        $name = ($parents != '') ? $parents . "->" . $product->collection->name : $product->collection->name;
                        $product->parents = $name;
                    }
                    $calls[$index]->product = $product;
                }
            }
        }


        if ($request->getMethod() == 'GET') {
            return view("supporters.supportercalls", [
                "supporter" => $supporter,
                "from_date" => $request->input('from_date'),
                "to_date" => $request->input('to_date'),
                "products_id" => $request->input('products_id'),
                "notices_id" => $request->input('notices_id'),
                'replier_id' => $request->input('replier_id'),
                "sources_id" => $request->input('sources_id'),
                'id' => $id,
                'supporter' => $supporter,
                "request" => request()->all(),
                "route" => 'user_supporter_acall'
            ]);
        } else {
            $req =  $request->all();
            if (!isset($req['start'])) {
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach ($calls as $index => $item) {

                $data[] = [
                    $index + 1,
                    $item->id,
                    $item->student->first_name . ' ' . $item->student->last_name,
                    ($item->product) ? (($item->product->parents != '-') ? $item->product->parents . '->' : '') . $item->product->name : '-',
                    ($item->notice) ? $item->notice->name : '-',
                    $persons[$item->replier],
                    ($item->callresult) ? $item->callresult->title : '-',
                    ($item->next_call) ? jdate($item->next_call)->format("Y/m/d") : '-',
                    ($item->next_to_call) ? $persons[$item->next_to_call] : '-',
                    ($item->created_at) ? jdate($item->created_at)->format("Y/m/d H:i:s") : jdate()->format("Y/m/d H:i:s"),
                    $item->description,
                    '<a class="btn btn-danger" onclick ="destroy(event)" href="' . route('user_supporter_delete_call', ["user_id" => $id, "id" => $item->id]) . '">حذف</a>'
                ];
            }


            $outdata = [];
            for ($i = $req['start']; $i < min($req['length'] + $req['start'], count($data)); $i++) {
                $outdata[] = $data[$i];
            }

            $result = [
                "draw" => $req['draw'],
                "data" => $outdata,
                "recordsTotal" => count($calls),
                "recordsFiltered" => count($calls)
            ];

            return $result;
        }
    }

    public function create(Request $request)
    {
        $groups = Group::all();
        $supportGroupId = Group::getSupport();
        if ($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $user = new User;
        if ($request->getMethod() == 'GET') {
            return view('supporters.create', [
                "groups" => $groups,
                "user" => $user
            ]);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->groups_id = $supportGroupId; //(int)$request->input('groups_id');
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
        if ($request->file('image_path')) {
            $filename = now()->timestamp . '.' . $request->file('image_path')->extension();
            $user->image_path = $request->file('image_path')->storeAs('supporters', $filename, 'public_uploads');
        }

        $find = User::where('email', $request->input('email'))->first();
        if ($find) {
            $request->session()->flash("msg_error", "نام کاربری قبلا استفاده شده است!");
            return view('supporters.create', [
                "groups" => $groups,
                "user" => $user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }


        if ($request->input('password') != $request->input('repassword')) {
            $request->session()->flash("msg_error", "رمز عبور و تکرار آن باید یکی باشند");
            return view('supporters.create', [
                "groups" => $groups,
                "user" => $user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }

        $user->save();

        $request->session()->flash("msg_success", "کاربر با موفقیت افزوده شد.");
        return redirect()->route('user_supporters');
    }

    public function students($id)
    {
        return $this->student($id);
    }
    public function findStudent($request, $students)
    {
        $request != null ? $students = $students->where('id', (int)$request) : $students = '';
    }

    public function student($id = null)
    {
        $user = null;
        $saleSuggestions = SaleSuggestion::all();
        if ($id == null) {
            $id = Auth::user()->id;
        } else {
            $user = User::find($id);
        }
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', $id);
        $sources = Source::where('is_deleted', false)->get();
        $products = Product::where('is_deleted', false)->with('collection')->orderBy('name')->get();
        foreach ($products as $index => $product) {
            $products[$index]->parents = "-";
        }
        $callResults = CallResult::where('is_deleted', false)->get();
        $notices = Notice::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        $students_id = null;
        $calls_id = null;
        $education_level = null;
        $has_collection = 'false';
        $has_the_product = '';
        $has_the_tags = '';
        $has_call_result = '';
        $has_site = 'false';
        $order_collection = 'false';
        $has_reminder = 'false';
        $has_tag = 'false';
        $appMergeStudents = null;
        if (request()->input('students_id') != null) {
            $students_id = (int)request()->input('students_id');
            $calls_id = (int)request()->input('calls_id');
            $students = $students->where('id', $students_id);
        }

        $this->findStudent(request()->input('main_id'), $students);
        $this->findStudent(request()->input('auxilary_id'), $students);
        $this->findStudent(request()->input('second_auxilary_id'), $students);
        $this->findStudent(request()->input('third_auxilary_id'), $students);

        if (request()->getMethod() == 'POST') {


            if (request()->input('name') != null) {
                $name = trim(request()->input('name'));
                $students = $students->where(function ($query) use ($name) {
                    $tmpNames = explode(' ', $name);
                    foreach ($tmpNames as $tmpName) {
                        $tmpName = trim($tmpName);
                        $query->orWhere('first_name', 'like', '%' . $tmpName . '%')->orWhere('last_name', 'like', '%' . $tmpName . '%');
                    }
                });
            }
            if (request()->input('sources_id') != null) {
                $sources_id = (int)request()->input('sources_id');
                $students = $students->where('sources_id', $sources_id);
            }
            if (request()->input('phone') != null) {
                $phone = (int)request()->input('phone');
                $students = $students->where('phone', $phone);
            }
            if (request()->input('has_collection') != null) {
                $has_collection = request()->input('has_collection');
                if ($has_collection == 'true') {
                    $studentCollections = StudentCollection::where('is_deleted', false)->pluck('students_id');
                    $students = $students->whereIn('id', $studentCollections);
                }
            }
            if (request()->input('has_the_product') != null && request()->input('has_the_product') != '') {
                $has_the_product = request()->input('has_the_product');
                $purchases = Purchase::where('is_deleted', false)->where('type', '!=', 'site_failed')->whereIn('products_id', explode(',', $has_the_product))->pluck('students_id');
                $students = $students->whereIn('id', $purchases);
            }
            if (request()->input('has_call_result') != null && request()->input('has_call_result') != '') {
                $has_call_result = request()->input('has_call_result');
                $calls = Call::where('call_results_id', $has_call_result);
                if ($has_the_product != '') {
                    $calls = $calls->whereIn('products_id', explode(',', $has_the_product));
                }
                $calls = $calls->pluck('students_id');
                // $students = $students->whereIn('id', $purchases)->orWhereIn('id', $calls);
                $students = $students->where(function ($query) use ($purchases, $calls) {
                    $query->whereIn('id', $purchases)->orWhereIn('id', $calls);
                });
            } else {
                if (request()->input('has_the_product') != null && request()->input('has_the_product') != '') {
                    $has_the_product = request()->input('has_the_product');
                    $purchases = Purchase::where('is_deleted', false)->where('type', '!=', 'site_failed')->whereIn('products_id', explode(',', $has_the_product))->pluck('students_id');
                    $students = $students->whereIn('id', $purchases);
                }
                if (request()->input('has_call_result') != null && request()->input('has_call_result') != '') {
                    $has_call_result = request()->input('has_call_result');
                    $calls = Call::where('call_results_id', $has_call_result);
                    if ($has_the_product != '') {
                        $calls = $calls->whereIn('products_id', explode(',', $has_the_product));
                    }
                    $calls = $calls->pluck('students_id');
                    $students = $students->whereIn('id', $calls);
                }
            }
            if (request()->input('has_the_tags') != null && request()->input('has_the_tags') != '') {
                $has_the_tags = request()->input('has_the_tags');
                $studentTags = StudentTag::where('is_deleted', false)->whereIn('tags_id', explode(',', $has_the_tags))->pluck('students_id');
                $students = $students->whereIn('id', $studentTags);
            }
            if (request()->input('has_site') != null) {
                $has_site = request()->input('has_site');
                if ($has_site == 'true') {
                    $purchases = Purchase::where('is_deleted', false)->where('type', 'site_successed')->pluck('students_id');
                    $students = $students->whereIn('id', $purchases);
                }
            }
            if (request()->input('has_reminder') != null) {
                $has_reminder = request()->input('has_reminder');
                if ($has_reminder == 'true') {
                    $students = $students->has('remindercalls');
                }
            }
            if (request()->input('has_tag') != null) {
                $has_tag = request()->input('has_tag');
                if ($has_tag == 'true') {
                    $students = $students->has('studenttags');
                }
            }
            if (request()->input('education_level') != null) {
                $egucation_level = request()->input('education_level');
                $students = $students->where('egucation_level', $egucation_level);
            }
            if (request()->input('major') != null) {
                $major = request()->input('major');
                $students = $students->where('major', $major);
            }
            if (request()->input('conditions') != null) {
                $condition_id = request()->input('conditions');
                $moralTags = Tag::where('type', 'moral')->where('is_deleted', false)->orderBy('name')->get();
                if ($condition_id) {
                    $suggestion = SaleSuggestion::where('id', $condition_id)->first();
                    $purchases = [];
                    $student_tags = [];
                    $need_tags = [];
                    $school = null;
                    $last_year_grade = 0;
                    $average = 0;
                    $source = 0;
                    if ($suggestion->if_products_id != null && $suggestion->if_products_id != "") {
                        $if_products_id = explode(',', $suggestion->if_products_id);
                        $purchases = Purchase::whereIn('products_id', $if_products_id)->pluck('students_id');
                    }
                    if ($suggestion->if_moral_tags_id != null && $suggestion->if_moral_tags_id != "") {
                        $if_moral_tags_id = explode(',', $suggestion->if_moral_tags_id);
                        $student_tags = StudentTag::whereIn('tags_id', $if_moral_tags_id)->where('is_deleted', false)->pluck('students_id');
                    }
                    if ($suggestion->if_need_tags_id != null && $suggestion->if_need_tags_id != "") {
                        $if_need_tags_id = explode(',', $suggestion->if_need_tags_id);
                        $need_tags = StudentTag::whereIn('tags_id', $if_need_tags_id)->where('is_deleted', false)->pluck('students_id');
                    }
                    if ($suggestion->if_schools_id != null && $suggestion->if_schools_id != "") {
                        $school = $suggestion->if_schools_id;
                    }
                    if ($suggestion->if_last_year_grade != 0) {
                        $last_year_grade = $suggestion->if_last_year_grade == null ? 0 : $suggestion->if_last_year_grade;
                    }
                    if ($suggestion->if_avarage != 0) {
                        $average = $suggestion->if_avarage == null ? 0 : $suggestion->if_avarage;
                    }
                    if ($suggestion->if_sources_id != 0) {
                        $source = $suggestion->if_sources_id == null ? 0 : $suggestion->if_sources_id;
                    }
                    $students = $students->where(function ($query) use ($purchases) {
                        if ($purchases) $query->whereIn('id', $purchases);
                    })
                        ->where(function ($query) use ($student_tags) {
                            if ($student_tags) $query->whereIn('id', $student_tags);
                        })
                        ->where(function ($query) use ($need_tags) {
                            if ($need_tags) $query->whereIn('id', $need_tags);
                        })
                        ->where(function ($query) use ($last_year_grade) {
                            if ($last_year_grade) $query->where('last_year_grade', '<=', $last_year_grade);
                        })
                        ->where(function ($query) use ($average) {
                            if ($average) $query->where('average', '>=', $average);
                        })
                        ->where(function ($query) use ($school) {
                            if ($school) $query->where('school', $school);
                        })
                        ->where(function ($query) use ($source) {
                            if ($source) $query->where('sources_id', $source);
                        });
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
            ->with('mergestudent.mainStudent')
            ->with('mergestudent.auxilaryStudent')
            ->with('mergestudent.secondAuxilaryStudent')
            ->with('mergestudent.thirdAuxilaryStudent')
            ->with('mergeauxilarystudent.mainStudent')
            ->with('mergeauxilarystudent.auxilaryStudent')
            ->with('mergeauxilarystudent.secondAuxilaryStudent')
            ->with('mergeauxilarystudent.thirdAuxilaryStudent')
            ->with('mergesecondauxilarystudent.mainStudent')
            ->with('mergesecondauxilarystudent.auxilaryStudent')
            ->with('mergesecondauxilarystudent.secondAuxilaryStudent')
            ->with('mergesecondauxilarystudent.thirdAuxilaryStudent')
            ->with('mergethirdauxilarystudent.mainStudent')
            ->with('mergethirdauxilarystudent.auxilaryStudent')
            ->with('mergethirdauxilarystudent.secondAuxilaryStudent')
            ->with('mergethirdauxilarystudent.thirdAuxilaryStudent')
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->input('order_collection') != null) {
            $order_collection = request()->input('order_collection');
            if ($order_collection == 'true') {
                $students = $students->sortBy(function ($hackathon) {
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
        $needTagParentOnes = NeedTagParentOne::where('is_deleted', false)->has('tags')->get();
        $needTagParentTwos = NeedTagParentTwo::where('is_deleted', false)->has('tags')->get();
        $needTagParentThrees = NeedTagParentThree::where('is_deleted', false)->has('tags')->get();
        $needTagParentFours = NeedTagParentFour::where('is_deleted', false)->has('tags')->get();

        $moralTags = Tag::where('is_deleted', false)->where('type', 'moral')->get();
        $needTags = Tag::where('is_deleted', false)->where('type', 'need')->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();
        $collections = Collection::where('is_deleted', false)->get();

        foreach ($students as $index => $student) {
            $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
            if ($students[$index]->calls)
                foreach ($students[$index]->calls as $cindex => $call) {
                    $students[$index]->calls[$cindex]->next_call = ($students[$index]->calls[$cindex]->next_call) ? jdate(strtotime($students[$index]->calls[$cindex]->next_call))->format("Y/m/d") : null;
                }
        }
        // dd($callResults);
        if (request()->getMethod() == 'GET') {
            // dd($has_the_product);
            return view('supporters.student', [
                'user' => $user,
                'students' => $students,
                'sources' => $sources,
                'name' => $name,
                'sources_id' => $sources_id,
                'phone' => $phone,
                'moralTags' => $moralTags,
                'needTags' => $needTags,
                'hotTemperatures' => $hotTemperatures,
                'coldTemperatures' => $coldTemperatures,
                "parentOnes" => $parentOnes,
                "parentTwos" => $parentTwos,
                "parentThrees" => $parentThrees,
                "parentFours" => $parentFours,
                "firstCollections" => $firstCollections,
                "secondCollections" => $secondCollections,
                "thirdCollections" => $thirdCollections,
                'products' => $products,
                'notices' => $notices,
                'callResults' => $callResults,
                'has_collection' => $has_collection,
                'has_the_product' => ($has_the_product != '') ? explode(',', $has_the_product) : '',
                'has_the_tags' => ($has_the_tags != '') ? explode(',', $has_the_tags) : '',
                'has_call_result' => $has_call_result,
                'has_site' => $has_site,
                'order_collection' => $order_collection,
                'has_reminder' => $has_reminder,
                'has_tag' => $has_tag,
                "needTagParentOnes" => $needTagParentOnes,
                "needTagParentTwos" => $needTagParentTwos,
                "needTagParentThrees" => $needTagParentThrees,
                "needTagParentFours" => $needTagParentFours,
                "students_id" => $students_id,
                "calls_id" => $calls_id,
                "saleSuggestions" => $saleSuggestions,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        } else {
            $req =  request()->all();
            // dd($req);
            if (!isset($req['start'])) {
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach ($students as $index => $item) {
                $tags = "";
                if (($item->studenttags && count($item->studenttags) > 0) || ($item->studentcollections && count($item->studentcollections) > 0)) {
                    for ($i = 0; $i < count($item->studenttags); $i++) {
                        $tags .= '<span class="alert alert-' . (($item->studenttags[$i]->tag->type == 'moral') ? 'info' : 'warning') . ' p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '') . ' ' . $item->studenttags[$i]->tag->name . '
                        </span><br/>';
                    }
                    // for($i = 0; $i < count($item->studentcollections);$i++){
                    //     if(isset($item->studentcollections[$i]->collection))
                    //         $tags .= '<span class="alert alert-warning p-1">
                    //             '. (($item->studentcollections[$i]->collection->parent) ? $item->studentcollections[$i]->collection->parent->name . '->' : '' ) . ' ' . $item->studentcollections[$i]->collection->name .'
                    //         </span><br/>';
                    // }
                }
                $registerer = "-";
                if ($item->user)
                    $registerer =  $item->user->first_name . ' ' . $item->user->last_name;
                elseif ($item->saloon)
                    $registerer = $item->saloon;
                elseif ($item->is_from_site)
                    $registerer =  'سایت';

                $temps = "";
                if ($item->studenttemperatures && count($item->studenttemperatures) > 0) {
                    foreach ($item->studenttemperatures as $sitem) {
                        if ($sitem->temperature->status == 'hot')
                            $temps .= '<span class="alert alert-danger p-1">';
                        else
                            $temps .= '<span class="alert alert-info p-1">';
                        $temps .= $sitem->temperature->name . '</span>';
                    }
                }
                $data[] = [
                    $index + 1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source) ? $item->source->name : '-',
                    $tags,
                    $temps,
                    ""
                ];
            }

            $outdata = [];
            for ($i = $req['start']; $i < min($req['length'] + $req['start'], count($data)); $i++) {
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

    public function newStudents()
    {
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporter_seen', false)->where('supporters_id', Auth::user()->id);
        $sources = Source::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        if (request()->getMethod() == 'POST') {
            // dump(request()->all());
            if (request()->input('name') != null) {
                $name = trim(request()->input('name'));
                $students = $students->where(function ($query) use ($name) {
                    $query->where('first_name', 'like', '%' . $name . '%')->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }
            if (request()->input('sources_id') != null) {
                $sources_id = (int)request()->input('sources_id');
                $students = $students->where('sources_id', $sources_id);
            }
            if (request()->input('phone') != null) {
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
        $needTags = Tag::where('is_deleted', false)
            // ->with('parent_one')
            // ->with('parent_two')
            // ->with('parent_three')
            // ->with('parent_four')
            ->where('type', 'need')
            ->get();
        $parentOnes = TagParentOne::has('tags')->get();
        $parentTwos = TagParentTwo::has('tags')->get();
        $parentThrees = TagParentThree::has('tags')->get();
        $parentFours = TagParentFour::has('tags')->get();
        $needTagParentOnes = NeedTagParentOne::where('is_deleted', false)->has('tags')->get();
        $needTagParentTwos = NeedTagParentTwo::where('is_deleted', false)->has('tags')->get();
        $needTagParentThrees = NeedTagParentThree::where('is_deleted', false)->has('tags')->get();
        $needTagParentFours = NeedTagParentFour::where('is_deleted', false)->has('tags')->get();
        $collections = Collection::where('is_deleted', false)->get();
        $firstCollections = Collection::where('is_deleted', false)->where('parent_id', 0)->get();
        $secondCollections = Collection::where('is_deleted', false)->whereIn('parent_id', $firstCollections->pluck('id'))->get();
        $thirdCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $secondCollections->pluck('id'))->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();

        foreach ($students as $index => $student) {
            $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
        }

        if (request()->getMethod() == 'GET') {
            return view('supporters.new', [
                'students' => $students,
                'sources' => $sources,
                'name' => $name,
                'sources_id' => $sources_id,
                'phone' => $phone,
                'moralTags' => $moralTags,
                'needTags' => $needTags,
                'hotTemperatures' => $hotTemperatures,
                'coldTemperatures' => $coldTemperatures,
                "parentOnes" => $parentOnes,
                "parentTwos" => $parentTwos,
                "parentThrees" => $parentThrees,
                "parentFours" => $parentFours,
                "firstCollections" => $firstCollections,
                "secondCollections" => $secondCollections,
                "thirdCollections" => $thirdCollections,
                "needTagParentOnes" => $needTagParentOnes,
                "needTagParentTwos" => $needTagParentTwos,
                "needTagParentThrees" => $needTagParentThrees,
                "needTagParentFours" => $needTagParentFours,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        } else {
            $req =  request()->all();
            // dd($req);
            if (!isset($req['start'])) {
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach ($students as $index => $item) {
                $tags = "";
                if (($item->studenttags && count($item->studenttags) > 0) || ($item->studentcollections && count($item->studentcollections) > 0)) {
                    for ($i = 0; $i < count($item->studenttags); $i++) {
                        $tags .= '<span class="alert alert-' . (($item->studenttags[$i]->tag->type == 'moral') ? 'info' : 'warning') . ' p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '') . ' ' . $item->studenttags[$i]->tag->name . '
                    </span><br/>';
                    }
                    // for($i = 0; $i < count($item->studentcollections);$i++){
                    //     $tags .= '<span class="alert alert-warning p-1">
                    //         '. (($item->studentcollections[$i]->collection->parent) ? $item->studentcollections[$i]->collection->parent->name . '->' : '' ) . ' ' . $item->studentcollections[$i]->collection->name .'
                    //     </span><br/>';
                    // }
                }
                $registerer = "-";
                if ($item->user)
                    $registerer =  $item->user->first_name . ' ' . $item->user->last_name;
                elseif ($item->saloon)
                    $registerer = $item->saloon;
                elseif ($item->is_from_site)
                    $registerer =  'سایت';

                $temps = "";
                if ($item->studenttemperatures && count($item->studenttemperatures) > 0) {
                    foreach ($item->studenttemperatures as $sitem) {
                        if ($sitem->temperature->status == 'hot')
                            $temps .= '<span class="alert alert-danger p-1">';
                        else
                            $temps .= '<span class="alert alert-info p-1">';
                        $temps .= $sitem->temperature->name . '</span>';
                    }
                }
                $data[] = [
                    $index + 1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source) ? $item->source->name : '-',
                    $tags,
                    $temps,
                    $item->description,
                    '<a class="btn btn-success btn-sm" href="#" onclick="return seeStudent(this, ' . $item->id . ');">
                    مشاهده شد
                    </a>'
                ];
            }

            $outdata = [];
            for ($i = $req['start']; $i < min($req['length'] + $req['start'], count($data)); $i++) {
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

    public function getPurchases()
    {
        $supports = [];
        $sources = Source::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $phone = null;
        $moralTags = Tag::where('is_deleted', false)
            // ->with('parent_one')
            // ->with('parent_two')
            // ->with('parent_three')
            // ->with('parent_four')
            ->where('type', 'moral')
            ->get();
        $students = [];
        if (Gate::allows('purchases')) {
            $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->get();
        }
        else {
            $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', Auth::user()->id)->get();
        }
        foreach ($students as $index => $item) {
            $item->today_purchases = $item->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where('type','!=','site_failed')->where('is_deleted',false)->count();
            $item->save();
        }

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
        $needTagParentOnes = NeedTagParentOne::where('is_deleted', false)->has('tags')->get();
        $needTagParentTwos = NeedTagParentTwo::where('is_deleted', false)->has('tags')->get();
        $needTagParentThrees = NeedTagParentThree::where('is_deleted', false)->has('tags')->get();
        $needTagParentFours = NeedTagParentFour::where('is_deleted', false)->has('tags')->get();
        $products = Product::where('is_deleted', false)->get();
        $finalStudents = [];
        return view('supporters.purchase', [
            'students' => $finalStudents,
            'sources' => $sources,
            'name' => $name,
            'sources_id' => $sources_id,
            'phone' => $phone,
            'moralTags' => $moralTags,
            'needTags' => $collections,
            'hotTemperatures' => $hotTemperatures,
            'coldTemperatures' => $coldTemperatures,
            "parentOnes" => $parentOnes,
            "parentTwos" => $parentTwos,
            "parentThrees" => $parentThrees,
            "parentFours" => $parentFours,
            "firstCollections" => $firstCollections,
            "secondCollections" => $secondCollections,
            "thirdCollections" => $thirdCollections,
            "needTagParentOnes" => $needTagParentOnes,
            "needTagParentTwos" => $needTagParentTwos,
            "needTagParentThrees" => $needTagParentThrees,
            "needTagParentFours" => $needTagParentFours,
            "supports" => $supports,
            "products" => $products,
            'msg_success' => request()->session()->get('msg_success'),
            'msg_error' => request()->session()->get('msg_error')
        ]);
    }
    public function postPurchases(Request $request)
    {
        $supports = [];
        $students = [];
        if (Gate::allows('purchases')) {
            $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false);
            $supportGroupId = Group::getSupport();
            if ($supportGroupId)
                $supportGroupId = $supportGroupId->id;
            $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        } else {
            $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false)->where('supporters_id', Auth::user()->id);
        }
        $sources = Source::where('is_deleted', false)->get();
        $name = null;
        $sources_id = null;
        $products_id = null;
        $phone = null;
        $supporters_id = null;
        $from_date = null;
        $to_date = null;
        if ($request->input('name') != null) {
            $name = trim(request()->input('name'));
            $students = $students->where(function ($query) use ($name) {
                $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%' . $name . '%');
            });
        }
        if ($request->input('sources_id') != null) {
            $sources_id = (int)request()->input('sources_id');
            $students = $students->where('sources_id', $sources_id);
        }
        if ($request->input('phone') != null) {
            $phone = (int)request()->input('phone');
            $students = $students->where('phone', $phone);
        }
        if ($request->input('products_id') != null) {
            $products_id = (int)request()->input('products_id');
            $studentIds = Purchase::where('products_id', $products_id)->where('is_deleted', false)->where('type', '!=', 'site_failed')->pluck('students_id');
            $students = $students->whereIn('id', $studentIds);
        }
        if (Gate::allows('purchases')) {
            if ($request->input('supporters_id') != null) {
                $supporters_id = (int)request()->input('supporters_id');
                $students = $students->where('supporters_id', $supporters_id);
            }
            if ($request->input('from_date') != null) {
                $from_date = request()->input('from_date');
                $studentIds = Purchase::where('created_at', '>=', $from_date)->where('is_deleted', false)->where('type', '!=', 'site_failed')->pluck('students_id');
                $students = $students->whereIn('id', $studentIds);
            }
            if ($request->input('to_date') != null) {
                $to_date = request()->input('to_date');
                $studentIds = Purchase::where('created_at', '<=', $to_date)->where('is_deleted', false)->where('type', '!=', 'site_failed')->pluck('students_id');
                $students = $students->whereIn('id', $studentIds);
            }
        }
        $allStudents = $students->orderBy('id', 'desc')->get();

        $req =  request()->all();
        if (!isset($req['start'])) {
            $req['start'] = 0;
            $req['length'] = 10;
            $req['draw'] = 1;
        }
        $students = $students
            ->with('user')
            ->with('studenttags.tag')
            ->with('studentcollections.collection')
            ->with('studenttags.tag.parent_four')
            ->with('studenttemperatures.temperature')
            ->with('source')
            ->with('consultant')
            ->with('supporter')
            ->orderBy('created_at', 'desc')
            ->offset($req['start'])
            ->limit($req['length'])
            ->get();
        $data = [];
        foreach ($students as $index => $item) {
            if ($item->supporters_id) {
                $item->own_purchases = $item->purchases()->where('supporters_id', $item->supporters_id)
                   ->where('is_deleted',false)->where('type','!=','site_failed')->count();
            }
            $item->other_purchases = $item->purchases()->where(function($query) use($item){
                if($item->supporters_id)$query->where('supporters_id','!=',$item->supporters_id)->orWhere('supporters_id',0);
            })->where('is_deleted',false)->where('type','!=','site_failed')->count();
            $item->today_purchases = $item->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->where(function ($query) use ($products_id) {
                if ($products_id != null) $query->where('products_id', $products_id);
            })->where('type','!=','site_failed')->where('is_deleted',false)->count();
            $item->save();
        }
        foreach ($students as $index => $item) {
            $data[] = [
                $req['start'] + $index + 1,
                $item->id,
                $item->first_name,
                $item->last_name,
                $item->other_purchases,
                $item->own_purchases,
                $item->today_purchases,
                ""
            ];
        }
        $result = [
            "draw" => $req['draw'],
            "data" => $data,
            "recordsTotal" => count($allStudents),
            "recordsFiltered" => count($allStudents),
        ];

        return $result;
    }

    public function deleteCall($users_id, $id)
    {
        $call = Call::find($id);
        if ($call)
            $call->delete();
        return redirect()->route('supporter_student_allcall', ["id" => $users_id]);
    }
    public function newDeleteCall($users_id, $id)
    {
        $call = Call::find($id);
        if ($call)
            $call->delete();
        return redirect()->route('user_supporter_acall', ["id" => $users_id]);
    }

    public function calls($id)
    {
        $student = Student::where('id', $id)->where('banned', false)->with('calls.product')->with('calls.product.collection')->with('calls.callresult')->with('calls.notice')->first();
        if ($student->calls)
            foreach ($student->calls as $index => $call) {
                if ($student->calls[$index]->product) {
                    $student->calls[$index]->product->parents = "-";
                    if ($student->calls[$index]->product->collection) {
                        $parents = $student->calls[$index]->product->collection->parents();
                        $name = ($parents != '') ? $parents . "->" . $student->calls[$index]->product->collection->name : $student->calls[$index]->product->collection->name;
                        $student->calls[$index]->product->parents = $name;
                    }
                }
            }
        return view('supporters.call', [
            "student" => $student
        ]);
    }

    public function studentCreate()
    {
        $student = new Student();
        $supportGroupId = Group::getSupport();
        if ($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $consultantGroupId = Group::getConsultant();
        if ($consultantGroupId)
            $consultantGroupId = $consultantGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $consultants = User::where('is_deleted', false)->where('groups_id', $consultantGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        $cities = City::where('is_deleted', false)->get();
        if (request()->getMethod() == 'GET') {
            return view('students.create', [
                "supports" => $supports,
                "cities" => $cities,
                "consultants" => $consultants,
                "sources" => $sources,
                "student" => $student
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
        try {
            $student->save();
        } catch (Exception $e) {
            // dd($e);
            $msg = "خطا در ثبت رخ داد است";
            if ($e->getCode() == 23000)
                $msg = "شماره تلفن تکراری است!";

            request()->session()->flash("msg_error", $msg);
            return redirect()->route('supporter_student_new');
        }

        request()->session()->flash("msg_success", "دانش آموز با موفقیت افزوده شد.");
        return redirect()->route('supporter_student_new');
    }
    //---------------------AJAX-----------------------------------
    public function call(Request $request)
    {
        $students_id = $request->input('students_id');

        $student = Student::where('id', $students_id)->where('banned', false)->where('is_deleted', false)->first();
        if ($student == null) {
            return [
                "error" => "student_not_found",
                "data" => null
            ];
        }

        $ids = [];
        if ($request->input('products_id') == null) {
            $call = new Call;
            $call->title = 'تماس';
            $call->students_id = $students_id;
            $call->users_id = Auth::user()->id;
            $call->description = $request->input('description');
            $call->call_results_id = $request->input('call_results_id');
            $call->replier = $request->input('replier');
            $call->next_to_call = $request->input('next_to_call');
            $call->next_call = $request->input('next_call');
            $call->notices_id = ($request->input('notices_id') == null) ? 0 : $request->input('notices_id');
            $call->products_id = 0;
            $call->calls_id = $request->input('calls_id');
            try {
                $call->save();
                $ids[] = $call->id;
            } catch (Exception $e) {
                dump($e);
            }
        } else {
            foreach ($request->input('products_id') as $products_id) {
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
                $call->notices_id = ($request->input('notices_id') == null) ? 0 : $request->input('notices_id');
                $call->calls_id = $request->input('calls_id');
                try {
                    $call->save();
                    $ids[] = $call->id;
                } catch (Exception $e) {
                    dump($e);
                }
            }
        }

        return [
            "error" => null,
            "data" => $ids
        ];
    }

    public function changePass(Request $request)
    {
        if (!Gate::allows('parameters')) {
            return [
                "error" => "permission denied",
                "data" => null
            ];
        }

        $user_id = $request->input('user_id');

        $user = User::where('id', $user_id)->where('is_deleted', false)->first();
        if ($user == null) {
            return [
                "error" => "user_not_found",
                "data" => null
            ];
        }

        $user->password = Hash::make($request->input('password'));
        $user->pass = $request->input('password');
        $user->save();

        return [
            "error" => null,
            "data" => null
        ];
    }

    public function seen(Request $request)
    {
        if ($request->input('student_id') == null) {
            return [
                "error" => "InvalidInput",
                "data" => null
            ];
        }

        $student = Student::find($request->input('student_id'));
        if ($student == null) {
            return [
                "error" => "StudentNotFound",
                "data" => null
            ];
        }

        $student->supporter_seen = true;
        $student->save();
        return [
            "error" => null,
            "data" => $student
        ];
    }
}
