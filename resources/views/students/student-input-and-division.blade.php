@php
    $majors = [
        "mathematics"=>"ریاضی",
        "experimental"=>"تجربی",
        "humanities"=>"انسانی",
        "art"=>"هنر",
        "other"=>"دیگر"
    ];
    $egucation_levels = [
        "6" => "6",
        "7" => "7",
        "8" => "8",
        "9" => "9",
        "10" => "10",
        "11" => "11",
        "12" => "12",
        "13" => "فارغ التحصیل",
        "14" => "دانشجو",
        null => ""
    ];
@endphp
@extends('layouts.index')

@section('css')
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .morepanel{
        display: none;
    }
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>دانش آموزان</h1>
            </div>
            <div class="col-sm-6">
              <!--
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">DataTables</li>
              </ol>
              -->
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                    <a class="btn btn-success" href="{{ route('student_create') }}">دانش آموز جدید</a>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <h3 class="text-center">
                   فیلتر
                </h3>
                <form method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="supporters_id" onchange="theChange()">پشتیبان</label>
                                <select  id="supporters_id" name="supporters_id" class="form-control select2">
                                    <option value="">همه</option>
                                    @foreach ($supports as $item)
                                        @if(isset($supporters_id) && $supporters_id==$item->id)
                                        <option value="{{ $item->id }}" selected >
                                        @else
                                        <option value="{{ $item->id }}" >
                                        @endif
                                        {{ $item->first_name }} {{ $item->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="sources_id">منبع</label>
                                <select  id="sources_id" name="sources_id" class="form-control" onchange="theChange()">
                                    <option value="">همه</option>
                                    @foreach ($sources as $item)
                                        @if(isset($sources_id) && $sources_id==$item->id)
                                        <option value="{{ $item->id }}" selected >
                                        @else
                                        <option value="{{ $item->id }}" >
                                        @endif
                                        {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name">نام و نام خانوادگی</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی" value="{{ isset($name)?$name:'' }}" onkeypress="handle(event)" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="phone">تلفن</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="تلفن"  value="{{ isset($phone)?$phone:'' }}" onkeypress="handle(event)" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="cities_id">شهر</label>
                                <select  id="cities_id" name="cities_id" class="form-control select2" onchange="theChange()">
                                    <option value="">همه</option>
                                    @foreach ($cities as $item)
                                        @if(isset($cities_id) && $cities_id==$item->id)
                                        <option value="{{ $item->id }}" selected >
                                        @else
                                        <option value="{{ $item->id }}" >
                                        @endif
                                        {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="egucation_level">مقطع</label>
                                <select  id="egucation_level" name="egucation_level" class="form-control" onchange="theChange()">
                                    <option value="">همه</option>
                                    @foreach ($egucation_levels as $key => $item)
                                        @if($key!=null)
                                        @if(isset($egucation_level) && $egucation_level==$key)
                                        <option value="{{ $key }}" selected >
                                        @else
                                        <option value="{{ $key }}" >
                                        @endif
                                        {{ $item }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="major">رشته</label>
                                <select  id="major" name="major" class="form-control" onchange="theChange()">
                                    <option value="">همه</option>
                                    @foreach ($majors as $key => $item)
                                        @if(isset($egucation_level) && $egucation_level==$key)
                                        <option value="{{ $key }}" selected >
                                        @else
                                        <option value="{{ $key }}" >
                                        @endif
                                        {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="school">مدرسه</label>
                                <input type="text" class="form-control" id="school" name="school" placeholder="مدرسه"  value="{{ isset($school)?$school:'' }}" onkeypress="handle(event)" />
                            </div>
                        </div>
                        <div class="col" style="padding-top: 32px;">
                            <a class="btn btn-success" onclick="theSearch()" href="#">
                                جستجو
                            </a>
                            <img id="loading" src="/dist/img/loading.gif" style="height: 20px;display: none;" />
                        </div>
                    </div>
                </form>

                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    <th>کاربر ثبت کننده</th>
                    <th>منبع ورودی شماره</th>
                    <th>برچسب</th>
                    <th>داغ/سرد</th>
                    <th>پشتیبان</th>
                    <th>توضیحات</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
@endsection

@section('js')
<div class="modal" id="tag_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">برچسب</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>
                <input type="hidden" id="students_index" />
                <div class="morals">
                <h3 class="text-center">
                    اخلاقی
                </h3>
                <div>
                    <select id="parent-one" onchange="selectParentOne(this);">
                        <option value="">همه</option>
                        @foreach ($parentOnes as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="parent-two" onchange="selectParentTwo(this);">
                        <option value="">همه</option>
                        @foreach ($parentTwos as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="parent-three" onchange="selectParentThree(this);">
                        <option value="">همه</option>
                        @foreach ($parentThrees as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="parent-four" onchange="selectParentFour(this);">
                        <option value="">همه</option>
                        @foreach ($parentFours as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                @foreach ($moralTags as $index => $item)
                    <input type="checkbox" class="tag-checkbox" id="tag_{{ $item->id }}" value="{{ $item->id }}" />
                    <span class="tag-title" id="tag-title-{{ $item->id }}">
                    {{ $item->name }}
                    </span>
                    <br class="tag-br" id="tag-br-{{ $item->id }}"/>
                @endforeach
                </div>
                <div class="needs">
                <h3 class="text-center">
                    نیازسنجی
                </h3>
                <div>
                    <select id="need-parent-one" onchange="selectNeedParentOne(this);">
                        <option value="">همه</option>
                        @foreach ($needTagParentOnes as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="need-parent-two" onchange="selectNeedParentTwo(this);">
                        <option value="">همه</option>
                        @foreach ($needTagParentTwos as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="need-parent-three" onchange="selectNeedParentThree(this);">
                        <option value="">همه</option>
                        @foreach ($needTagParentThrees as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="need-parent-four" onchange="selectNeedParentFour(this);">
                        <option value="">همه</option>
                        @foreach ($needTagParentFours as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                @foreach ($needTags as $index => $item)
                    <input type="checkbox" class="needtag-checkbox" id="needtag_{{ $item->id }}" value="{{ $item->id }}" />
                    <span class="needtag-title" id="needtag-title-{{ $item->id }}">
                    {{ $item->name }}
                    </span>
                    <br class="needtag-br" id="needtag-br-{{ $item->id }}"/>
                @endforeach
                <!--
                <div>
                    <select id="collection-one" onchange="selectCollectionOne(this);">
                        <option value="">همه</option>
                        @foreach ($firstCollections as $item)
                        <option value="{{ $item->id }}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="collection-two" onchange="selectCollectionTwo(this);">
                        <option value="">همه</option>
                        @foreach ($secondCollections as $item)
                        <option value="{{ $item->id }}" data-parent_id="{{$item->parent_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    <select id="collection-three" onchange="selectCollectionThree(this);">
                        <option value="">همه</option>
                        @foreach ($thirdCollections as $item)
                        <option value="{{ $item->id }}" data-parent_id="{{$item->parent_id}}" data-parent_parent_id="{{$item->parent->parent_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                @foreach ($needTags as $index => $item)
                    <input type="checkbox" class="collection-checkbox" id="collection_{{ $item->id }}" value="{{ $item->id }}" />
                    <span class="collection-title" id="collection-title-{{ $item->id }}">
                    {{ $item->name }}
                    </span>
                    <br class="collection-br" id="collection-br-{{ $item->id }}"/>
                @endforeach
                -->
                </div>
            </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="saveTags();">اعمال</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
        </div>
      </div>
    </div>
</div>
<div class="modal" id="temperature_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">داغ/سرد</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>
                <input type="hidden" id="students_index2" />
                <h3 class="text-center">
                    داغ
                </h3>
                @foreach ($hotTemperatures as $index => $item)
                    <input type="checkbox" class="temperature-checkbox" id="temperature_{{ $item->id }}" value="{{ $item->id }}" />
                    {{ $item->name }}
                @endforeach
                <h3 class="text-center">
                    سرد
                </h3>
                @foreach ($coldTemperatures as $index => $item)
                    <input type="checkbox" class="temperature-checkbox" id="temperature_{{ $item->id }}" value="{{ $item->id }}" />
                    {{ $item->name }}
                @endforeach
            </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="saveTemperatures();">اعمال</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
        </div>
      </div>
    </div>
</div>
<!-- Select2 -->
<script src="/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- page script -->
<script>
    let students = @JSON($students);
    let parentOnes = @JSON($parentOnes);
    let parentTwos = @JSON($parentTwos);
    let parentThrees = @JSON($parentThrees);
    let parentFours = @JSON($parentFours);
    let tmpTags = @JSON($moralTags);
    let tmpCollections = @JSON($needTags);
    let egucation_levels = @JSON($egucation_levels);
    let majors = @JSON($majors);
    let tags = {};
    let collections = {};
    let stu_id = 0;
    let index = 0;
    var table;
    for(let tg of tmpTags){
        tags[tg.id] = tg;
    }
    for(let cl of tmpCollections){
        collections[cl.id] = cl;
    }
    let filterParents = {
        parent1: '',
        parent2: '',
        parent3: '',
        parent4: '',
        need_parent1: '',
        need_parent2: '',
        need_parent3: '',
        need_parent4: ''
    }
    function showMorePanel(index, tr){
        var editRoute = `{{ route('student_edit', ['call_back'=>'student_all', 'id'=>-1]) }}`;
        var purchaseRoute = `{{ route('student_purchases', -1) }}`;
        var test = `<table style="width: 100%">
            <tr>
                <td>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                تراز یا رتبه سال قبل :
                                ${ (students[index].last_year_grade!=null)?students[index].last_year_grade:'' }
                            </div>
                            <div class="col">
                                مشاور :
                                ${ (students[index].consultant)?students[index].consultant.first_name + ' ' + students[index].consultant.last_name:'' }
                            </div>
                            <div class="col">
                                شغل پدر یا مادر :
                                ${ (students[index].parents_job_title!=null)?students[index].parents_job_title:'' }
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                شماره منزل :
                                ${ (students[index].home_phone!=null)?students[index].home_phone:'' }
                            </div>
                            <div class="col">
                                مقطع :
                                ${ (students[index].egucation_level!=null)?((egucation_levels[students[index].egucation_level])?egucation_levels[students[index].egucation_level]:students[index].egucation_level):'' }
                            </div>
                            <div class="col">
                                شماره موبایل والدین :
                                ${ (students[index].father_phone!=null)?students[index].father_phone:'' }
                                ${ (students[index].mother_phone!=null)?students[index].mother_phone:'' }
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                مدرسه :
                                ${ (students[index].school)!=null?students[index].school:'' }
                            </div>
                            <div class="col">
                                معدل :
                                ${ (students[index].average!=null)?students[index].average:'' }
                            </div>
                            <div class="col">
                                رشته تحصیلی :
                                ${ (majors[students[index].major])?majors[students[index].major]:'-' }
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <a href="${ editRoute.replace('-1', students[index].id) }">
                                    ویرایش مشخصات
                                </a>
                            </div>
                            <div class="col">
                                تاریخ ثبت دانش آموز :
                                ${ students[index].pcreated_at }
                            </div>
                            <div class="col">
                                تلفن دانش آموز:
                               ${ students[index].phone}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href="#" onclick="$('#students_index').val(${ index });preloadTagModal('moral');$('#tag_modal').modal('show'); return false;">
                                    برچسب روحیات اخلاقی
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a href="#" onclick="$('#students_index').val(${ index });preloadTagModal('need');$('#tag_modal').modal('show'); return false;">
                                    برچسب نیازهای دانش آموز
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <a target="_blank" href="${ purchaseRoute.replace('-1', students[index].id) }">
                                    گزارش خریدهای قطعی دانش آموز
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>`;

        // var tr = $("#tr-" + index)[0];
        var row = table.row(tr);
        if ( row.child.isShown() ) {
            row.child.hide();
        }
        else {
            row.child( test ).show();
        }
    }
    function changeSupporter(studentsIndex,id){
        if(students[studentsIndex]){
            var students_id = id;
            var supporters_id = $("#supporters_id_" + studentsIndex).val();
            $("#loading-" + studentsIndex).show();
            $.post('{{ route('student_supporter') }}', {
                students_id,
                supporters_id
            }, function(result){
                $("#loading-" + studentsIndex).hide();
                if(result && result.error!=null){
                    alert(result.error);
                }
            }).fail(function(){
                $("#loading-" + studentsIndex).hide();
                console.log(result);
                alert('خطای بروز رسانی');
                table.ajax.reload();
            });
        }
        return false;
    }
    function selectParentOne(dobj){
        filterParents.parent1 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterTagsByParent()
    }
    function selectParentTwo(dobj){
        filterParents.parent2 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterTagsByParent()
    }
    function selectParentThree(dobj){
        filterParents.parent3 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterTagsByParent()
    }
    function selectParentFour(dobj){
        filterParents.parent4 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterTagsByParent()
    }
    function filterTagsByParent(){
        $("input.tag-checkbox").show();
        $("span.tag-title").show();
        $("br.tag-br").show();
        $("input.tag-checkbox").each(function (id, field){
           // console.log('checking', field)
            let tagId = parseInt($(field).val(), 10);
            let theTag = tags[tagId];
            //console.log(tagId, theTag)
            if(theTag){
                if(filterParents.parent1!=''){
                    if(filterParents.parent1!=theTag.parent1){
                        $(field).hide();
                        $("#tag-title-" + tagId).hide();
                        $("#tag-br-" + tagId).hide();
                    }
                }
                if(filterParents.parent2!=''){
                    if(filterParents.parent2!=theTag.parent2){
                        $(field).hide();
                        $("#tag-title-" + tagId).hide();
                        $("#tag-br-" + tagId).hide();
                    }
                }
                if(filterParents.parent3!=''){
                    if(filterParents.parent3!=theTag.parent3){
                        $(field).hide();
                        $("#tag-title-" + tagId).hide();
                        $("#tag-br-" + tagId).hide();
                    }
                }
                if(filterParents.parent4!=''){
                    if(filterParents.parent4!=theTag.parent4){
                        $(field).hide();
                        $("#tag-title-" + tagId).hide();
                        $("#tag-br-" + tagId).hide();
                    }
                }
            }

        });
    }
    function selectNeedParentOne(dobj){
        filterParents.need_parent1 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterNeedTagsByParent()
    }
    function selectNeedParentTwo(dobj){
        filterParents.need_parent2 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterNeedTagsByParent()
    }
    function selectNeedParentThree(dobj){
        filterParents.need_parent3 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterNeedTagsByParent()
    }
    function selectNeedParentFour(dobj){
        filterParents.need_parent4 = ($(dobj).val()!='')?parseInt($(dobj).val(), 10):'';
        filterNeedTagsByParent()
    }
    function filterNeedTagsByParent(){
        $("input.needtag-checkbox").show();
        $("span.needtag-title").show();
        $("br.needtag-br").show();
        $("input.needtag-checkbox").each(function (id, field){
           // console.log('checking', field)
            let tagId = parseInt($(field).val(), 10);
            let theTag = collections[tagId];
            //console.log(tagId, theTag)
            if(theTag){
                if(filterParents.need_parent1!=''){
                    if(filterParents.need_parent1!=theTag.need_parent1){
                        $(field).hide();
                        $("#needtag-title-" + tagId).hide();
                        $("#needtag-br-" + tagId).hide();
                    }
                }
                if(filterParents.need_parent2!=''){
                    if(filterParents.need_parent2!=theTag.need_parent2){
                        $(field).hide();
                        $("#needtag-title-" + tagId).hide();
                        $("#needtag-br-" + tagId).hide();
                    }
                }
                if(filterParents.need_parent3!=''){
                    if(filterParents.need_parent3!=theTag.need_parent3){
                        $(field).hide();
                        $("#needtag-title-" + tagId).hide();
                        $("#needtag-br-" + tagId).hide();
                    }
                }
                if(filterParents.need_parent4!=''){
                    if(filterParents.need_parent4!=theTag.need_parent4){
                        $(field).hide();
                        $("#needtag-title-" + tagId).hide();
                        $("#needtag-br-" + tagId).hide();
                    }
                }
            }

        });
    }
    function selectCollectionOne(dobj){
        $("#collection-two").find('option').show();
        $("#collection-two").find('option[value=""]').prop('selected', true);
        if($(dobj).val()!=''){
            $("#collection-two").find('option').each(function(id, field){
                if($(field).data('parent_id')!=$(dobj).val() && $(field).val()!=''){
                    $(field).hide();
                }else{
                    $(field).show();
                }
            });
        }
        $("#collection-three").find('option').show();
        $("#collection-three").find('option[value=""]').prop('selected', true);
        if($(dobj).val()!=''){
            $("#collection-three").find('option').each(function(id, field){
                if($(field).data('parent_parent_id')!=$(dobj).val() && $(field).val()!=''){
                    $(field).hide();
                }else{
                    $(field).show();
                }
            });
        }
        filterCollectionsByParent();
    }
    function selectCollectionTwo(dobj){
       // console.log('hey');
        $("#collection-three").find('option').show();
        $("#collection-three").find('option[value=""]').prop('selected', true);
        if($(dobj).val()!=''){
            $("#collection-three").find('option').each(function(id, field){
                if($(field).data('parent_id')!=$(dobj).val()){
                    $(field).hide();
                }else{
                    $(field).show();
                }
            });
        }
        filterCollectionsByParent();
    }
    function selectCollectionThree(dobj){
       // console.log('hey3');
        filterCollectionsByParent();
    }
    function filterCollectionsByParent(){
        $("input.collection-checkbox").show();
        $("span.collection-title").show();
        $("br.collection-br").show();
        let collectionParents = $("#collection-two").val();
        let parents = [];
        if($("#collection-one").val()=='' && collectionParents==''){
            return false;
        }




        if(collectionParents==''){
            parents.push(parseInt($("#collection-one").val(), 10));
            $("#collection-two").find('option').each(function(id, field){
                if($(field).css('display')!='none' && !isNaN(parseInt($(field).val(), 10))){
                    parents.push(parseInt($(field).val(), 10));
                }
            });
            console.log('p1', parents);
            $("input.collection-checkbox").each(function (id, field){
                let collectionId = parseInt($(field).val(), 10);
                if(parents.indexOf(collectionId)<0){
                    $(field).hide();
                    $("#collection-title-" + collectionId).hide();
                    $("#collection-br-" + collectionId).hide();
                }
            });
            return false;
        }else {
            parents.push(parseInt(collectionParents, 10))
        }


        if($("#collection-three").val()==''){
            parents.push(parseInt($("#collection-two").val(), 10));
            $("#collection-three").find('option').each(function(id, field){
                if($(field).css('display')!='none' && !isNaN(parseInt($(field).val(), 10))){
                    parents.push(parseInt($(field).val(), 10));
                }
            });
            console.log('p2', parents);
            $("input.collection-checkbox").each(function (id, field){
                let collectionId = parseInt($(field).val(), 10);
                if(parents.indexOf(collectionId)<0){
                    $(field).hide();
                    $("#collection-title-" + collectionId).hide();
                    $("#collection-br-" + collectionId).hide();
                }
            });
            return false;
        }else {
            parents.push(parseInt($("#collection-three").val(), 10))
        }

        console.log('parents:', parents);

        $("input.collection-checkbox").each(function (id, field){
            // console.log('checking', field)
            let collectionId = parseInt($(field).val(), 10);
            let theCollection = collections[collectionId];
            //console.log(collectionId, theCollection)
            if(theCollection){
                console.log(parents.indexOf(theCollection.id), parents.indexOf(theCollection.parent_id));
                if(parents.indexOf(theCollection.id)<0 && parents.indexOf(theCollection.parent_id)<0){
                    $(field).hide();
                    $("#collection-title-" + collectionId).hide();
                    $("#collection-br-" + collectionId).hide();
                }
            }

        });
    }
    function preloadTagModal(mode){
        if(mode=='need'){
            $("div.needs").show();
            $("div.morals").hide();
        }else{
            $("div.needs").hide();
            $("div.morals").show();
        }
        $("input.tag-checkbox").prop('checked', false);
        $("input.collection-checkbox").prop('checked', false);
        var studentsIndex = parseInt($("#students_index").val(), 10);
        if(!isNaN(studentsIndex)){
            if(students[studentsIndex]){
                console.log(students[studentsIndex].studenttags);
                for(studenttag of students[studentsIndex].studenttags){
                    $("#tag_" + studenttag.tags_id).prop("checked", true);
                    $("#needtag_" + studenttag.tags_id).prop("checked", true);
                }
                console.log(students[studentsIndex].studentcollections);
                for(studentcollection of students[studentsIndex].studentcollections){
                    $("#collection_" + studentcollection.collections_id).prop("checked", true);
                }

            }
        }
    }
    function onClickTemperature(id){
        stu_id = id;
        preloadTemperatureModal(id);
        $('#temperature_modal').modal('show'); 
        return false;
    }
    function findIndexOfTemperatures(id){
        for(var i = 0; i < students.length; i++){
          if(students[i].id == id) {
              index = i;
          }
        }
        return index;
    }
    function preloadTemperatureModal(id){
        $("input.temperature-checkbox").prop('checked', false);
        index = findIndexOfTemperatures(id);
        if(!isNaN(index)){
            if(students[index]){
                for(studenttemperature of students[index].studenttemperatures){
                    $("#temperature_" + studenttemperature.temperatures_id).prop("checked", true);
                }
            }
        }
    }
    function saveTags(){
        var selectedTags = [];
        var selectedColllections = [];
        $("input.tag-checkbox:checked").each(function (id , field){
            selectedTags.push(parseInt(field.value, 10));
        });
        $("input.needtag-checkbox:checked").each(function (id , field){
            selectedColllections.push(parseInt(field.value, 10));
        });
        var studentsIndex = parseInt($("#students_index").val(), 10);
        if(!isNaN(studentsIndex)){
            if(students[studentsIndex]){
                console.log('selected tags', selectedTags);
                console.log('selected collections', selectedColllections);
                $.post('{{ route('student_tag') }}', {
                    students_id: students[studentsIndex].id,
                    selectedTags,
                    selectedColllections
                }, function(result){
                    console.log('Result', result);
                    if(result.error!=null){
                        alert('خطای بروز رسانی');
                    }else{
                        window.location.reload();
                    }
                }).fail(function(){
                    alert('خطای بروز رسانی');
                });
            }
        }
    }
    function saveTemperatures(){
        var selectedTemperatures = [];
        $("input.temperature-checkbox:checked").each(function (id , field){
            selectedTemperatures.push(parseInt(field.value, 10));
        });
        index = findIndexOfTemperatures(stu_id);
        if(!isNaN(index)){
            if(students[index]){
                console.log('selected temperatures', selectedTemperatures);
                $.post('{{ route('student_temperature') }}', {
                    students_id: stu_id,
                    selectedTemperatures
                }, function(result){
                    console.log('Result', result);
                    if(result.error!=null){
                        alert('خطای بروز رسانی');
                    }else{
                        window.location.reload();
                    }
                }).fail(function(){
                    alert('خطای بروز رسانی');
                });
            }
        }
    }
    function isEmpty(obj) {
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop)) {
            return false;
            }
        }

        return JSON.stringify(obj) === JSON.stringify({});
    }
    function theSearch(){
        $('#loading').css('display','inline');
        table.ajax.reload();
        return false;
    }
    function theChange(){
        $('#loading').css('display','inline');
        table.ajax.reload();
        return false;
    }
    function handle(e){
        if(e.keyCode === 13){
            $('#loading').css('display','inline');
            e.preventDefault(); // Ensure it is only this code that runs
            table.ajax.reload();
            return false;
        }
    }
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".btn-danger").click(function(e){
            if(!confirm('آیا مطمئنید؟')){
                e.preventDefault();
            }
        });
        $('select.select2').select2();

        table = $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "stateSave": true,
            "language": {
                "paginate": {
                    "previous": "قبل",
                    "next": "بعد"
                },
                "emptyTable":     "داده ای برای نمایش وجود ندارد",
                "info":           "نمایش _START_ تا _END_ از _TOTAL_ داده",
                "infoEmpty":      "نمایش 0 تا 0 از 0 داده",
                "proccessing": "در حال بروزرسانی"
            },
            "columnDefs": [   ////define column 1 and 10
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            {
                "searchable": false,
                "orderable": false,
                "targets": 10
            },
            {
                "searchable": false,
                "orderable": false,
                "targets": 7
            },
            ],
            "order": [[1, 'asc']], /// sort columns 2
            serverSide: true,
            processing: true,
            ajax: {
                "type": "POST",
                "url": "{{ route('students') }}",
                "dataType": "json",
                "contentType": 'application/json; charset=utf-8',

                "data": function (data) {
                    data['supporters_id'] = $("#supporters_id").val();
                    data['sources_id'] = $("#sources_id").val();
                    data['cities_id'] = $("#cities_id").val();
                    data['egucation_level'] = $("#egucation_level").val();
                    data['major'] = $("#major").val();
                    data['school'] = $("#school").val();
                    data['name'] = $("#name").val();
                    data['phone'] = $("#phone").val();

                    return JSON.stringify(data);
                },
                "complete": function(response) {
                   $('#loading').css('display','none');
                    //if(students && isEmpty(students))
                      //  students = JSON.parse(response.responseText).students;
                    $('#example2 tr').click(function() {
                        var tr = this;
                        var studentId = parseInt($(tr).find('td')[1].innerText, 10);
                        if(!isNaN(studentId)){
                            for(var index in students){
                                if(students[index].id==studentId){
                                    showMorePanel(index, tr);
                                }
                            }
                        }
                    });
                }

            },
            columns: [
                { data: null},
                { data: 'id' },
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'users_id' },
                { data: 'sources_id'},
                { data: 'tags'},
                { data: 'temps'},
                { data: 'supporters_id'},
                { data: 'description'},
                { data : 'end'}
            ],
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });


        $("#input").keyup(e => {
            console.log(e);
        });
    });
  </script>
@endsection
