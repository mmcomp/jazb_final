@php
$majors = [
    "mathematics"=>"ریاضی",
    "experimental"=>"تجربی",
    "humanities"=>"انسانی",
    "art"=>"هنر",
    "other"=>"دیگر"
];
$persons = [
    "student"=>"دانش آموز",
    "father"=>"پدر",
    "mother"=>"مادر",
    "other"=>"غیره"
];
$results = [
    "no_answer"=>"بدون پاسخ",
    "unsuccessful"=>"ناموفق",
    "successful"=>"موفق",
    "rejected"=>"در شده"
];
$egucation_levels = [
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
    .morepanel {
        display: none;
    }
    div.dataTables_wrapper {
        width : 100% !important;
    }
</style>
<!-- Date Picker -->
<link href="/plugins/persiancalender/jquery.md.bootstrap.datetimepicker.style.css" rel="stylesheet"/>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>دانش آموزان</h1>
                @if($user)
                <small>{{ $user->first_name }} {{ $user->last_name }}</small>
                @endif
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
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <h3 class="text-center">
                        فیلتر
                    </h3>
                    <form method="post" id="search-frm">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="sources_id">منبع</label>
                                    <select id="sources_id" name="sources_id" class="form-control">
                                        <option value="">همه</option>
                                        @foreach ($sources as $item)
                                        @if(isset($sources_id) && $sources_id==$item->id)
                                        <option value="{{ $item->id }}" selected>
                                            @else
                                        <option value="{{ $item->id }}">
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
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="نام و نام خانوادگی" value="{{ isset($name)?$name:'' }}" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone">تلفن</label>
                                    <input type="number" class="form-control" id="phone" name="phone" placeholder="تلفن"
                                        value="{{ isset($phone)?$phone:'' }}" />
                                </div>
                            </div>
                            <div class="col" style="padding-top: 32px;">
                                <button class="btn btn-success">
                                    جستجو
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="has_collection" name="has_collection" value="{{ isset($has_collection)?$has_collection:'false' }}" />
                        <input type="hidden" id="has_the_product" name="has_the_product" value="{{ isset($has_the_product[0])?implode(',', $has_the_product):'' }}" />
                        <input type="hidden" id="has_the_tags" name="has_the_tags" value="{{ isset($has_the_tags[0])?implode(',', $has_the_tags):'' }}" />
                        <input type="hidden" id="has_call_result" name="has_call_result" value="{{ isset($has_call_result)?$has_call_result:'' }}" />
                        <input type="hidden" id="has_site" name="has_site" value="{{ isset($has_site)?$has_site:'false' }}" />
                        <input type="hidden" id="order_collection" name="order_collection" value="{{ isset($order_collection)?$order_collection:'false' }}" />
                        <input type="hidden" id="has_reminder" name="has_reminder" value="{{ isset($has_reminder)?$has_reminder:'false' }}" />
                        <input type="hidden" id="has_tag" name="has_tag" value="{{ isset($has_tag)?$has_tag:'false' }}" />
                    </form>
                    <h3 class="text-center">
                        مرتب سازی
                    </h3>
                    <div class="row">
                        <div class="col text-center p-1">
                            @if(isset($has_collection) && $has_collection=='true')
                            <a class="btn btn-success btn-block" href="#" onclick="return StudentCollection();">پیشنهاد فروش</a>
                            @else
                            <a class="btn btn-warning btn-block" href="#" onclick="return StudentCollection();">پیشنهاد فروش</a>
                            @endif
                        </div>
                        <div class="col text-center p-1">
                            <!--<a class="btn btn-warning btn-block" href="#">محصول</a>-->
                            <!--
                            <label>
                                محصول:
                            </label>
                            -->
                            <select id="has_product" class="form-control select2" multiple onchange="return selectProduct();">
                                @if(isset($has_the_product[0]))
                                <option value="" disabled>محصول</option>
                                @else
                                <option selected value="" disabled>محصول</option>
                                @endif
                                <option value="">همه</option>
                                @foreach($products as $product)
                                @if(isset($has_the_product[0]) && in_array($product->id,$has_the_product))
                                <option value="{{ $product->id }}">{{($product->parents!='-')?$product->parents . '->':''}} {{ $product->name }}</option>
                                @else
                                <option value="{{ $product->id }}">{{($product->parents!='-')?$product->parents . '->':''}} {{ $product->name }}</option>
                                @endif
                                @endforeach
                            </select>
                            <select id="has_cal_result" class="form-control select2" onchange="return selectCallResult();">
                                @if(isset($has_call_result) && $has_call_result>0)
                                <option value="" disabled>نتیجه تماس</option>
                                @else
                                <option selected value="" disabled>نتیجه تماس</option>
                                @endif
                                <option value="">همه</option>
                                @foreach($callResults as $callResult)
                                @if(isset($has_call_result) && $has_call_result==$callResult->id)
                                <option value="{{ $callResult->id }}" selected>{{ $callResult->title }}</option>
                                @else
                                <option value="{{ $callResult->id }}">{{ $callResult->title }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col text-center p-1">
                            @if(isset($has_site) && $has_site=='true')
                            <a class="btn btn-success btn-block" href="#" onclick="return StudentSite();">سایت</a>
                            @else
                            <a class="btn btn-warning btn-block" href="#" onclick="return StudentSite();">سایت</a>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center p-1">
                            @if(isset($order_collection) && $order_collection=='true')
                            <a class="btn btn-success btn-block" href="#" onclick="return OrderCollection();">تعداد پیشنهاد فروش</a>
                            @else
                            <a class="btn btn-warning btn-block" href="#" onclick="return OrderCollection();">تعداد پیشنهاد فروش</a>
                            @endif
                        </div>
                        <div class="col text-center p-1">
                            @if(isset($has_reminder) && $has_reminder=='true')
                            <a class="btn btn-success btn-block" href="#" onclick="return StudentReminder();">یادآور</a>
                            @else
                            <a class="btn btn-warning btn-block" href="#" onclick="return StudentReminder();">یادآور</a>
                            @endif
                        </div>
                        <div class="col text-center p-1">
                            @if(isset($has_tag) && $has_tag=='true')
                            <a class="btn btn-success btn-block" href="#" onclick="return StudentTag();">برچسب اخلاقی دارد؟</a>
                            @else
                            <a class="btn btn-warning btn-block" href="#" onclick="return StudentTag();">برچسب اخلاقی دارد؟</a>
                            @endif
                            <select id="has_the_tag" class="form-control select2" multiple onchange="return selectTag();">
                                @if(isset($has_the_tags[0]))
                                <option value="" disabled>برچسب اخلاقی</option>
                                @else
                                <option selected value="" disabled>برچسب اخلاقی</option>
                                @endif
                                <option value="">همه</option>
                                @foreach($moralTags as $tag)
                                @if(isset($has_the_tags[0]) && in_array($tag->id,$has_the_tags))
                                <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                                @else
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col text-center p-1">
                            <a class="btn btn-warning btn-block" href="#">برچسب ارزیابی</a>
                        </div>
                        <div class="col text-center p-1">
                        </div>
                        <div class="col text-center p-1">
                        </div>
                    </div>-->
                    <table id="example2" class="table table-bordered table-hover _datatables">
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
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $item)
                            <tr>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ $index + 1 }}</td>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ $item->id }}</td>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ $item->first_name }}</td>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ $item->last_name }}</td>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ ($item->user)?$item->user->first_name . ' ' . $item->user->last_name:'-' }}</td>
                                <td onclick="showMorePanel({{ $index }});">
                                    {{ ($item->source)?$item->source->name:'-' }}</td>
                                @if($item->studenttags && count($item->studenttags)>0)
                                <td onclick="showMorePanel({{ $index }});">
                                    @for($i = 0; $i < count($item->studenttags);$i++)
                                        <span class="alert alert-info p-1">
                                            {{ $item->studenttags[$i]->tag->name }}
                                        </span>
                                        @endfor
                                </td>
                                @else
                                <td onclick="showMorePanel({{ $index }});"></td>
                                @endif
                                @if($item->studenttemperatures && count($item->studenttemperatures)>0)
                                <td onclick="showMorePanel({{ $index }});">
                                    @foreach ($item->studenttemperatures as $sitem)
                                    @if($sitem->temperature->status=='hot')
                                    <span class="alert alert-danger p-1">
                                        @else
                                        <span class="alert alert-info p-1">
                                            @endif
                                            {{ $sitem->temperature->name }}
                                        </span>
                                        @endforeach
                                </td>
                                @else
                                <td onclick="showMorePanel({{ $index }});"></td>
                                @endif
                                <td>
                                    <a class="btn btn-warning" href="#"
                                        onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
                                        برچسب
                                    </a>
                                    <a class="btn btn-warning" href="#"
                                        onclick="$('#students_index2').val({{ $index }});preloadTemperatureModal();$('#temperature_modal').modal('show'); return false;">
                                        داغ/سرد
                                    </a>
                                    <a class="btn btn-primary" href="{{ route('student_edit', $item->id) }}">
                                        ویرایش
                                    </a>
                                    <a class="btn btn-danger" href="{{ route('student_delete', $item->id) }}">
                                        حذف
                                    </a>
                                </td>
                            </tr>
                            <tr class="morepanel" id="morepanel-{{ $index }}">
                                <td colspan="9">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col">
                                                تراز یا رتبه سال قبل :
                                                {{ $item->last_year_grade }}
                                            </div>
                                            <div class="col">
                                                مشاور :
                                                {{ ($item->consultant)?$item->consultant->first_name . ' ' . $item->consultant->last_name:'' }}
                                            </div>
                                            <div class="col">
                                                شغل پدر یا مادر :
                                                {{ $item->parents_job_title }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                شماره منزل :
                                                {{ $item->home_phone }}
                                            </div>
                                            <div class="col">
                                                مقطع :
                                                {{ isset($egucation_levels[$item->egucation_level])?$egucation_levels[$item->egucation_level]:$item->egucation_level }}
                                            </div>
                                            <div class="col">
                                                شماره موبایل والدین :
                                                {{ $item->father_phone }}
                                                {{ $item->mother_phone }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                مدرسه :
                                                {{ $item->school }}
                                            </div>
                                            <div class="col">
                                                معدل :
                                                {{ $item->average }}
                                            </div>
                                            <div class="col">
                                                رشته تحصیلی :
                                                {{ isset($majors[$item->major])?$majors[$item->major]:'-' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a href="{{ route('student_edit', $item->id) }}">
                                                    ویرایش مشخصات
                                                </a>
                                            </div>
                                            <div class="col">
                                                تاریخ ثبت دانش آموز :
                                                {{ jdate(strtotime($item->created_at))->format("Y/m/d") }}
                                            </div>
                                            <div class="col">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a href="#"
                                                    onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
                                                    برچسب روحیات اخلاقی
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a href="#"
                                                    onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
                                                    برچسب نیازهای دانش آموز
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="{{ route('student_purchases', $item->id) }}">
                                                    گزارش خریدهای قطعی دانش آموز
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="{{ route('supporter_student_allcall', $item->id) }}">
                                                    گزارش تماس ها
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a class="btn btn-success" href="#" onclick="students_id = {{ $item->id }};$('#call_modal').modal('show');return false;">
                                                    ثبت تماس
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <table class="table table-bordered table-hover datatables-all datatables-{{ $index }}" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>ردیف</th>
                                                        <th>کد</th>
                                                        <th>محصول</th>
                                                        <th>پاسخگو</th>
                                                        <th>نتیجه</th>
                                                        <th>یادآور</th>
                                                        <th>پاسخگو بعد</th>
                                                        <th>توضیحات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->calls as $cindex => $call)
                                                    @if($cindex < 5)
                                                    <tr>
                                                        <td>{{ $cindex + 1 }}</td>
                                                        <td>{{ $call->id }}</td>
                                                        <td>{{ $call->product->name }}</td>
                                                        <td>{{ $persons[$call->replier] }}</td>
                                                        <td>{{ $call->callresult?$call->callresult->title:'-' }}</td>
                                                        <td>{{ $call->next_call }}</td>
                                                        <td>{{ ($call->next_call)?$persons[$call->next_to_call]:'' }}</td>
                                                        <td>{{ $call->description }}</td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td><td style="display: none;"></td>
                            </tr>
                            @endforeach
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
                    <h3 class="text-center">
                        اخلاقی
                    </h3>
                    @foreach ($moralTags as $index => $item)
                    <input type="checkbox" class="tag-checkbox" id="tag_{{ $item->id }}" value="{{ $item->id }}" />
                    {{ $item->name }}
                    @endforeach
                    <h3 class="text-center">
                        نیازسنجی
                    </h3>
                    @foreach ($needTags as $index => $item)
                    <input type="checkbox" class="collection-checkbox" id="collection_{{ $item->id }}"
                        value="{{ $item->id }}" />
                    {{ $item->name }}
                    @endforeach
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
                    <input type="checkbox" class="temperature-checkbox" id="temperature_{{ $item->id }}"
                        value="{{ $item->id }}" />
                    {{ $item->name }}
                    @endforeach
                    <h3 class="text-center">
                        سرد
                    </h3>
                    @foreach ($coldTemperatures as $index => $item)
                    <input type="checkbox" class="temperature-checkbox" id="temperature_{{ $item->id }}"
                        value="{{ $item->id }}" />
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
<div class="modal" id="call_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ثبت تماس</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <div class="form-group">
                        <label for="call_results_id">نتیجه</label>
                        <select class="form-control" id="call_results_id" name="call_results_id">
                            <!--
                            <option value="no_answer">بدون پاسخ</option>
                            <option value="unsuccessful">ناموفق</option>
                            <option value="successful">موفق</option>
                            <option value="rejected">رد شده</option>
                            -->
                            @foreach ($callResults as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="replier">پاسخگو</label>
                        <select class="form-control" id="replier" name="replier">
                            <option value="student">داشن آموز</option>
                            <option value="father">پدر</option>
                            <option value="mother">مادر</option>
                            <option value="other">غیره</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="products_id">محصول</label>
                        <select class="form-control select2" id="products_id" name="products_id[]" style="width: 100% !important;" multiple>
                            <option value=""></option>
                            @foreach ($products as $item)
                            <option value="{{ $item->id }}">{{($item->parents!='-')?$item->parents . '->':''}} {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="next_to_call">تماس بعد</label>
                        <select class="form-control" id="next_to_call" name="next_to_call">
                            <option value="student">داشن آموز</option>
                            <option value="father">پدر</option>
                            <option value="mother">مادر</option>
                            <option value="other">غیره</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">زمان تماس بعد</label>
                        <input type="text" class="form-control" id="next_call_persian"placeholder="زمان تماس بعد" readonly />
                        <input type="hidden" id="next_call" name="next_call" />
                    </div>
                    <div class="form-group">
                        <label for="description">توضیحات</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات"  />
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveCall();">ثبت</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
            </div>
        </div>
    </div>
</div>
<!-- Date Picker -->
<script src="/plugins/persiancalender/jquery.md.bootstrap.datetimepicker.js"></script>
<!-- Select2 -->
<script src="/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- page script -->
<script>
    let students = @JSON($students);
    let students_id;
    function showMorePanel(index){
        $('.morepanel').hide();
        $('#morepanel-' + index).show();
    }
    function changeSupporter(studentsIndex) {
        if (students[studentsIndex]) {
            var students_id = students[studentsIndex].id;
            var supporters_id = $("#supporters_id_" + studentsIndex).val();
            $("#loading-" + studentsIndex).show();
            $.post('{{ route('student_supporter') }}', {
                    students_id,
                    supporters_id
                },
                function (result) {
                    $("#loading-" + studentsIndex).hide();
                    console.log('Result', result);
                    if (result.error != null) {
                        alert('خطای بروز رسانی');
                    }
                }).fail(function () {
                $("#loading-" + studentsIndex).hide();
                alert('خطای بروز رسانی');
            });
        }
        return false;
    }

    function preloadTagModal() {
        $("input.tag-checkbox").prop('checked', false);
        $("input.collection-checkbox").prop('checked', false);
        var studentsIndex = parseInt($("#students_index").val(), 10);
        if (!isNaN(studentsIndex)) {
            if (students[studentsIndex]) {
                console.log(students[studentsIndex].studenttags);
                for (studenttag of students[studentsIndex].studenttags) {
                    $("#tag_" + studenttag.tags_id).prop("checked", true);
                }
                console.log(students[studentsIndex].studentcollections);
                for (studentcollection of students[studentsIndex].studentcollections) {
                    $("#collection_" + studentcollection.collections_id).prop("checked", true);
                }
            }
        }
    }

    function preloadTemperatureModal() {
        $("input.tag-checkbox").prop('checked', false);
        var studentsIndex = parseInt($("#students_index2").val(), 10);
        if (!isNaN(studentsIndex)) {
            if (students[studentsIndex]) {
                console.log(students[studentsIndex].studenttemperatures);
                for (studenttag of students[studentsIndex].studenttemperatures) {
                    $("#temperature_" + studenttag.temperatures_id).prop("checked", true);
                }
            }
        }
    }

    function saveTags() {
        var selectedTags = [];
        var selectedColllections = [];
        $("input.tag-checkbox:checked").each(function (id, field) {
            selectedTags.push(parseInt(field.value, 10));
        });
        $("input.collection-checkbox:checked").each(function (id, field) {
            selectedColllections.push(parseInt(field.value, 10));
        });
        var studentsIndex = parseInt($("#students_index").val(), 10);
        if (!isNaN(studentsIndex)) {
            if (students[studentsIndex]) {
                console.log('selected tags', selectedTags);
                console.log('selected collections', selectedColllections);
                $.post('{{ route('student_tag') }}', {
                        students_id: students[studentsIndex].id,
                        selectedTags,
                        selectedColllections
                    },
                    function (result) {
                        console.log('Result', result);
                        if (result.error != null) {
                            alert('خطای بروز رسانی');
                        } else {
                            window.location.reload();
                        }
                    }).fail(function () {
                    alert('خطای بروز رسانی');
                });
            }
        }
    }

    function saveTemperatures() {
        var selectedTemperatures = [];
        $("input.temperature-checkbox:checked").each(function (id, field) {
            selectedTemperatures.push(parseInt(field.value, 10));
        });
        var studentsIndex = parseInt($("#students_index2").val(), 10);
        if (!isNaN(studentsIndex)) {
            if (students[studentsIndex]) {
                console.log('selected temperatures', selectedTemperatures);
                $.post('{{ route('student_temperature') }}', {
                        students_id: students[studentsIndex].id,
                        selectedTemperatures
                    },
                    function (result) {
                        console.log('Result', result);
                        if (result.error != null) {
                            alert('خطای بروز رسانی');
                        } else {
                            window.location.reload();
                        }
                    }).fail(function () {
                    alert('خطای بروز رسانی');
                });
            }
        }
    }

    function saveCall() {
        $.post('{{ route('supporter_student_call') }}', {
                students_id,
                description: $("#description").val(),
                result: $("#result").val(),
                replier: $("#replier").val(),
                products_id: $("#products_id").val(),
                next_to_call: $("#next_to_call").val(),
                next_call: $("#next_call").val()
            },
            function (result) {
                console.log('Result', result);
                if (result.error != null) {
                    alert('خطای بروز رسانی');
                } else {
                    window.location.reload();
                }
            }).fail(function () {
            alert('خطای بروز رسانی');
        });
    }

    function StudentCollection(){
        var has_collection = $("#has_collection").val().trim();
        if(has_collection=='' || has_collection=='false'){
            has_collection = 'true';
        }else {
            has_collection = 'false';
        }
        $("#has_collection").val(has_collection);
        $("#search-frm").submit();
    }

    function StudentTag(){
        var has_tag = $("#has_tag").val().trim();
        if(has_tag=='' || has_tag=='false'){
            has_tag = 'true';
        }else {
            has_tag = 'false';
        }
        $("#has_tag").val(has_tag);
        $("#search-frm").submit();
        return false;
    }

    function StudentReminder(){
        var has_reminder = $("#has_reminder").val().trim();
        if(has_reminder=='' || has_reminder=='false'){
            has_reminder = 'true';
        }else {
            has_reminder = 'false';
        }
        $("#has_reminder").val(has_reminder);
        $("#search-frm").submit();
        return false;
    }

    function StudentSite() {
        var has_site = $("#has_site").val().trim();
        if(has_site=='' || has_site=='false'){
            has_site = 'true';
        }else {
            has_site = 'false';
        }
        $("#has_site").val(has_site);
        $("#search-frm").submit();
        return false;
    }

    function OrderCollection() {
        var order_collection = $("#order_collection").val().trim();
        if(order_collection=='' || order_collection=='false'){
            order_collection = 'true';
        }else {
            order_collection = 'false';
        }
        $("#order_collection").val(order_collection);
        $("#search-frm").submit();
        return false;
    }

    function selectProduct(){
        $("#has_the_product").val($("#has_product").val().join(','));
        // $("#search-frm").submit();
    }

    function selectTag(){
        $("#has_the_tags").val($("#has_the_tag").val().join(','));
    }

    function selectCallResult(){
        $("#has_call_result").val($("#has_cal_result").val());
        $("#search-frm").submit();
    }

    $(function () {
        $('#next_call_persian').MdPersianDateTimePicker({
            targetTextSelector: '#next_call_persian',
            targetDateSelector: '#next_call',
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".btn-danger").click(function (e) {
            if (!confirm('آیا مطمئنید؟')) {
                e.preventDefault();
            }
        });
        $('select.select2').select2();

        $("table.datatables").DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language": {
                "paginate": {
                    "previous": "قبل",
                    "next": "بعد"
                },
                "emptyTable": "داده ای برای نمایش وجود ندارد",
                "info": "نمایش _START_ تا _END_ از _TOTAL_ داده",
                "infoEmpty": "نمایش 0 تا 0 از 0 داده",
            }
        });
    });

</script>
@endsection
