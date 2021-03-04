<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StudentController;
use App\Student;
use App\User;
use App\Source;
use App\Group;
use App\Collection;
use App\City;
use App\Http\Traits\ChangeSupporterTrait;
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
use Illuminate\Support\Facades\DB;
use App\MergeStudents as AppMergeStudents;
use Illuminate\Http\Request;
use Exception;


class assignGroupsOfStudentsToASponserController extends Controller
{
    use ChangeSupporterTrait;
    public function updateTodayPurchases($ids)
    {
        foreach ($ids as $id) {
            $stu = Student::where('id', $id)->with('purchases')->first();
            if ($stu) {
                $stu->today_purchases = $stu->purchases()->where('created_at', '>=', date("Y-m-d 00:00:00"))->count();
                $stu->save();
            }
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = [];
        $sw = -1;
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false);
        $supportGroupId = Group::getSupport();
        if ($supportGroupId)
            $supportGroupId = $supportGroupId->id;
        $supports = User::where('is_deleted', false)->where('groups_id', $supportGroupId)->get();
        $sources = Source::where('is_deleted', false)->get();
        $supporters_id = null;
        $name = null;
        $sources_id = null;
        $phone = null;
        $cities_id = null;
        $egucation_level = null;
        $school = null;
        $major = null;
        if (request()->getMethod() == 'POST') {
            // dump(request()->all());
            if (request()->input('supporters_id') != null) {
                $supporters_id = (int)request()->input('supporters_id');
                $students = $students->where('supporters_id', $supporters_id);
            }
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
            if (request()->input('cities_id') != null) {
                $cities_id = (int)request()->input('cities_id');
                $students = $students->where('cities_id', $cities_id);
            }
            if (request()->input('egucation_level') != null) {
                $egucation_level = request()->input('egucation_level');
                $students = $students->where('egucation_level', $egucation_level);
            }
            if (request()->input('major') != null) {
                $major = request()->input('major');
                $students = $students->where('major', $major);
            }
            if (request()->input('school') != null) {
                $school = request()->input('school');
                $students = $students->where('school', 'like',  '%' . $school . '%');
            }
        }
        $moralTags = Tag::where('is_deleted', false)

            ->where('type', 'moral')
            ->get();
        $needTags = Tag::where('is_deleted', false)

            ->where('type', 'need')
            ->get();
        $parentOnes = TagParentOne::where('is_deleted', false)->has('tags')->get();
        $parentTwos = TagParentTwo::where('is_deleted', false)->has('tags')->get();
        $parentThrees = TagParentThree::where('is_deleted', false)->has('tags')->get();
        $parentFours = TagParentFour::where('is_deleted', false)->has('tags')->get();
        $needTagParentOnes = NeedTagParentOne::where('is_deleted', false)->has('tags')->get();
        $needTagParentTwos = NeedTagParentTwo::where('is_deleted', false)->has('tags')->get();
        $needTagParentThrees = NeedTagParentThree::where('is_deleted', false)->has('tags')->get();
        $needTagParentFours = NeedTagParentFour::where('is_deleted', false)->has('tags')->get();
        $collections = Collection::where('is_deleted', false)->get();
        $firstCollections = Collection::where('is_deleted', false)->where('parent_id', 0)->get();
        $secondCollections = Collection::where('is_deleted', false)->whereIn('parent_id', $firstCollections->pluck('id'))->get();
        $thirdCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $secondCollections->pluck('id'))->get();
        // $fourthCollections = Collection::where('is_deleted', false)->with('parent')->whereIn('parent_id', $thirdCollections->pluck('id'))->get();
        $hotTemperatures = Temperature::where('is_deleted', false)->where('status', 'hot')->get();
        $coldTemperatures = Temperature::where('is_deleted', false)->where('status', 'cold')->get();
        $cities = City::where('is_deleted', false)->get();



        if (request()->getMethod() == 'GET') {
            return view('assign_students.index', [
                'route' => 'assign_students_index',
                'students' => $students,
                'supports' => $supports,
                'sources' => $sources,
                'supporters_id' => $supporters_id,
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
                // "fourthCollections"=>$fourthCollections,
                "cities" => $cities,
                "cities_id" => $cities_id,
                "egucation_level" => $egucation_level,
                "major" => $major,
                "needTagParentOnes" => $needTagParentOnes,
                "needTagParentTwos" => $needTagParentTwos,
                "needTagParentThrees" => $needTagParentThrees,
                "needTagParentFours" => $needTagParentFours,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        } else {
            $allStudents = $students->orderBy('id', 'desc')->get();
            $destination_supporter = request()->input('destination_supporter');
            $arrOfCheckBoxes = request()->input('arrOfCheckBoxes');
            $req =  request()->all();
            if (!isset($req['start'])) {
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $students = $students
                ->with('user')
                ->with('studenttags.tag.parent_four')
                ->with('studentcollections.collection.parent')
                ->with('studenttemperatures.temperature')
                ->with('source')
                ->with('consultant')
                ->with('supporter')
                ->orderBy('created_at', 'desc')
                ->offset($req['start'])
                ->limit($req['length'])
                ->get();
            foreach ($students as $index => $student) {
                $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
                $ids[] = $student->id;
            }

            if ($arrOfCheckBoxes == 'all') {
                $arrOfCheckBoxes = $ids;
            } else {
                $arrOfCheckBoxes = explode(',', $arrOfCheckBoxes);
            }
            if ($destination_supporter != null && !empty($arrOfCheckBoxes)) {
                $sw = null;
                $mergeStudents = AppMergeStudents::where('is_deleted', false)->get();
                $mergeStudentsArr = [];
                $helperyArr = [];
                $arr = [];
                foreach ($mergeStudents as $index => $item) {
                    if ($item->main_students_id) {
                        $helperyArr[] = $item->main_students_id;
                    }
                    if ($item->auxilary_students_id) {
                        $helperyArr[] = $item->auxilary_students_id;
                    }
                    if ($item->second_auxilary_students_id) {
                        $helperyArr[] = $item->second_auxilary_students_id;
                    }
                    if ($item->third_auxilary_students_id) {
                        $helperyArr[] = $item->third_auxilary_students_id;
                    }
                }

                foreach ($mergeStudents as $index => $item) {
                    $mergeStudentsArr[$item->main_students_id] = array_filter([$item->auxilary_students_id, $item->second_auxilary_students_id, $item->third_auxilary_students_id]);
                }
                $arr = array_diff($arrOfCheckBoxes, $helperyArr);
                foreach ($mergeStudentsArr as $main => $auxilaries) {
                    $count_auxilary = count(array_intersect($auxilaries, $arrOfCheckBoxes));
                    $count_arr = count(array_intersect($arr, $arrOfCheckBoxes));
                    if ((in_array($main, $arrOfCheckBoxes) && $count_auxilary) || in_array($main, $arrOfCheckBoxes)) {
                        $arrOfCheckBoxes = array_unique(array_merge($arrOfCheckBoxes, $auxilaries));
                        $sw = "main"; //all main or one main and other are auxilary or main and auixlary and other or one main and others
                    } else if (!in_array($main, $arrOfCheckBoxes) &&  $count_auxilary && !$count_arr) {
                        $arrOfCheckBoxes = array_diff($arrOfCheckBoxes, array_intersect($auxilaries, $arrOfCheckBoxes));
                        $sw = "auxilary_and_not_main_and_not_other"; //all auxilaries
                    } else if (!in_array($main, $arrOfCheckBoxes) &&  !$count_auxilary && $count_arr) {
                        $sw = "other"; //all other
                    }
                }
                foreach ($mergeStudentsArr as $main => $auxilaries) {
                    $count_auxilary = count(array_intersect($auxilaries, $arrOfCheckBoxes));
                    $count_arr = count(array_intersect($arr, $arrOfCheckBoxes));
                    if ((!in_array($main, $arrOfCheckBoxes) &&  $count_auxilary && $count_arr)) {
                        $arrOfCheckBoxes = array_diff($arrOfCheckBoxes, array_intersect($auxilaries, $arrOfCheckBoxes));
                        $sw = "auxilary_and_other";
                    }
                }
                $this->updateTodayPurchases($arrOfCheckBoxes);
                Student::whereIn('id', $arrOfCheckBoxes)
                    ->with('purchases')
                    ->update([
                        'supporters_id' => $destination_supporter,
                        'supporter_seen' => false,
                        'supporter_start_date' => date("Y-m-d H:i:s"),
                        'other_purchases' => DB::raw('other_purchases + own_purchases'),
                        'own_purchases' => 0
                    ]);
            }
            $names = Student::where('is_deleted',false)->where('banned',false)->where('archived',false)->whereIn('id',$arrOfCheckBoxes)->get();
            $arrOfNames = [];
            foreach($names as $name){
                $arrOfNames[] = $name->first_name.' '.$name->last_name;
            }
            // if ($sw == 1) {
            //     request()->session()->flash("msg_success", $message);
            //     return redirect()->route('assign_students_index');
            // } else if (!$sw) {
            //     request()->session()->flash("msg_error", $message);
            //     return redirect()->route('assign_students_index');
            // }
            // if (!isset($req['start'])) {
            //     $req['start'] = 0;
            //     $req['length'] = 10;
            //     $req['draw'] = 1;
            // }
            $data = [];
            foreach ($students as $index => $item) {
                $tags = "";
                if (($item->studenttags && count($item->studenttags) > 0) || ($item->studentcollections && count($item->studentcollections) > 0)) {
                    for ($i = 0; $i < count($item->studenttags); $i++) {
                        $tags .= '<span class="d-inline-block px-1 rounded small  mt-2 ' . (($item->studenttags[$i]->tag->type == 'moral') ? 'bg-cyan' : 'bg-warning') . ' p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '') . ' ' . $item->studenttags[$i]->tag->name . '
                    </span><br/>';
                    }
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
                            $temps .= '<span class="bg-danger d-inline-block px-1 rounded small p-1 mt-2">';
                        else
                            $temps .= '<span class="bg-info d-inline-block px-1 rounded small p-1 mt-2">';
                        $temps .= $sitem->temperature->name . '</span>';
                    }
                }
                $supportersToSelect = "";
                foreach ($supports as $sitem) {
                    $supportersToSelect .= '<option value="' . $sitem->id . '"';
                    if ($sitem->id == $item->supporters_id)
                        $supportersToSelect .= ' selected';
                    $supportersToSelect .= '>' . $sitem->first_name . ' ' . $sitem->last_name . '</option>';
                }
                $selectCheckBox = "<div class='form-check'>
                                     <input type='checkbox' class='form-check-input theCheckBoxes' id='ch_$item->id' value='$item->id' onchange='myFunc(this)'>
                                  </div>";
                //foreach($students as $index => $item){
                $data[] = [
                    $selectCheckBox,
                    $req['start'] + $index + 1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source) ? $item->source->name : '-',
                    $tags,
                    $temps,
                    '<select id="supporters_id_' . $index . '" class="form-control select2">
                            <option>-</option>
                            ' . $supportersToSelect . '
                            </select>
                            <a class="btn btn-success btn-sm" href="#" onclick="return changeSupporter(' . $index . "," . $item->id . ');">
                                ذخیره
                            </a>
                            <br/>
                            <img id="loading-' . $index . '" src="/dist/img/loading.gif" style="height: 20px;display: none;" />',
                    $item->description,
                    '<a class="btn btn-warning" href="#" onclick="$(\'#students_index2\').val(' . $index . ');preloadTemperatureModal();$(\'#temperature_modal\').modal(\'show\'); return false;">
                            داغ/سرد
                        </a>
                        <a class="btn btn-danger" href="' . route('student_class', ['id' => $item->id]) . '" >
                            تخصیص کلاس
                        </a>'
                ];
                //}

            }

            $result = [
                "draw" => $req['draw'],
                "data" => $data,
                "recordsTotal" => count($allStudents),
                "recordsFiltered" => count($allStudents),
                "students" => $students,
                "ids" => $ids,
                "checkboxes" => $arrOfNames,
                "sw" => $sw,
            ];

            return $result;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
