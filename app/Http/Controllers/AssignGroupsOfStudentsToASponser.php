<?php

namespace App\Http\Controllers;
use App\Student;
use App\User;
use App\Source;
use App\Group;
use App\Collection;
use App\City;
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
use Illuminate\Http\Request;

class assignGroupsOfStudentsToASponser extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = [];
        $sw = 0;
        $students = Student::where('is_deleted', false)->where('banned', false)->where('archived', false);
        $supportGroupId = Group::getSupport();
        if($supportGroupId)
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
        if(request()->getMethod()=='POST'){
            // dump(request()->all());
            if(request()->input('supporters_id')!=null){
                $supporters_id = (int)request()->input('supporters_id');
                $students = $students->where('supporters_id', $supporters_id);
            }
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
            if(request()->input('cities_id')!=null){
                $cities_id = (int)request()->input('cities_id');
                $students = $students->where('cities_id', $cities_id);
            }
            if(request()->input('egucation_level')!=null){
                $egucation_level = request()->input('egucation_level');
                $students = $students->where('egucation_level', $egucation_level);
            }
            if(request()->input('major')!=null){
                $major = request()->input('major');
                $students = $students->where('major', $major);
            }
            if(request()->input('school')!=null){
                $school = request()->input('school');
                $students = $students->where('school', 'like',  '%' . $school . '%');
            }
        }
        // DB::enableQueryLog();

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
        // dd($needTags);
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



        // dd($students);
        if(request()->getMethod()=='GET'){
            return view('assign_students.index',[
                'route' => 'assign_students_index',
                'students' => $students,
                'supports' => $supports,
                'sources' => $sources,
                'supporters_id' => $supporters_id,
                'name' => $name,
                'sources_id' => $sources_id,
                'phone' => $phone,
                'moralTags'=>$moralTags,
                'needTags'=>$needTags,
                'hotTemperatures'=>$hotTemperatures,
                'coldTemperatures'=>$coldTemperatures,
                "parentOnes"=>$parentOnes,
                "parentTwos"=>$parentTwos,
                "parentThrees"=>$parentThrees,
                "parentFours"=>$parentFours,
                "firstCollections"=>$firstCollections,
                "secondCollections"=>$secondCollections,
                "thirdCollections"=>$thirdCollections,
                // "fourthCollections"=>$fourthCollections,
                "cities"=>$cities,
                "cities_id"=>$cities_id,
                "egucation_level"=>$egucation_level,
                "major"=>$major,
                "needTagParentOnes"=>$needTagParentOnes,
                "needTagParentTwos"=>$needTagParentTwos,
                "needTagParentThrees"=>$needTagParentThrees,
                "needTagParentFours"=>$needTagParentFours,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else {
            $students = $students
                ->with('user')
                ->with('studenttags.tag.parent_four')
                ->with('studentcollections.collection.parent')
                ->with('studenttemperatures.temperature')
                ->with('source')
                ->with('consultant')
                ->with('supporter')
                ->orderBy('created_at', 'desc')
                ->get();
            foreach($students as $index => $student) {
                $students[$index]->pcreated_at = jdate(strtotime($student->created_at))->format("Y/m/d");
                $ids[] = $student->id;
            }
            $req =  request()->all();
            $destination_supporter = request()->input('destination_supporter');
            $arrOfCheckBoxes = request()->input('arrOfCheckBoxes');
            if($arrOfCheckBoxes == 'all'){
                $arrOfCheckBoxes = $ids;
            }else{
            $arrOfCheckBoxes = explode(',',$arrOfCheckBoxes);
            }

            if($destination_supporter != null && !empty($arrOfCheckBoxes)){
               foreach($arrOfCheckBoxes as $checkbox){
                   $stu = Student::where('id',$checkbox)->first();
                   if($stu){
                    $stu->supporters_id = $destination_supporter;
                    $stu->save();
                    $sw = 1;
                   }

               }

            }
            if($sw){
                request()->session()->flash("msg_success", "پشتیبان این افراد با موفقیت تغییر کرد.");
                return redirect()->route('assign_students_index');
            }
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
                        $tags .= '<span class="alert alert-' . (($item->studenttags[$i]->tag->type=='moral')?'info':'warning') . ' p-1">
                        ' . (($item->studenttags[$i]->tag->parent_four) ? $item->studenttags[$i]->tag->parent_four->name . '->' : '' ) . ' ' . $item->studenttags[$i]->tag->name . '
                    </span><br/>';
                    }
                    // for($i = 0; $i < count($item->studentcollections);$i++){
                    //     $tags .= '<span class="alert alert-warning p-1">
                    //         '. (($item->studentcollections[$i]->collection->parent) ? $item->studentcollections[$i]->collection->parent->name . '->' : '' ) . ' ' . $item->studentcollections[$i]->collection->name .'
                    //     </span><br/>';
                    // }
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
                $supportersToSelect = "";
                foreach ($supports as $sitem){
                    $supportersToSelect .= '<option value="' . $sitem->id . '"';
                    if ($sitem->id==$item->supporters_id)
                        $supportersToSelect .= ' selected';
                    $supportersToSelect .= '>' . $sitem->first_name . ' ' . $sitem->last_name . '</option>';
                }
                $selectCheckBox = "<div class='form-check'>
                                     <input type='checkbox' class='form-check-input' id='ch_$item->id' value='$item->id' onclick='myFunc(this)'>
                                  </div>";

                $data[] = [
                    $selectCheckBox,
                    $index+1,
                    $item->id,
                    $item->first_name,
                    $item->last_name,
                    $registerer,
                    ($item->source)?$item->source->name:'-',
                    $tags,
                    $temps,
                    '<select id="supporters_id_' . $index . '" class="form-control select2">
                        <option>-</option>
                        ' . $supportersToSelect . '
                        </select>
                        <a class="btn btn-success btn-sm" href="#" onclick="return changeSupporter(' . $index . "," . $item->id.');">
                            ذخیره
                        </a>
                        <br/>
                        <img id="loading-' . $index . '" src="/dist/img/loading.gif" style="height: 20px;display: none;" />',
                    $item->description,
                    '<a class="btn btn-warning" href="#" onclick="$(\'#students_index2\').val(' . $index . ');preloadTemperatureModal();$(\'#temperature_modal\').modal(\'show\'); return false;">
                        داغ/سرد
                    </a>
                    <a class="btn btn-danger" href="' . route('student_class', ['id'=>$item->id]) . '" >
                        تخصیص کلاس
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
                "recordsFiltered" => count($students),
                "students"=>$students,
                "ids" => $ids
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
