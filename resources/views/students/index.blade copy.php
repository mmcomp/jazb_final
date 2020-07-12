@php
    $majors = [
        "mathematics"=>"ریاضی",
        "experimental"=>"تجربی",
        "humanities"=>"انسانی",
        "art"=>"هنر",
        "other"=>"دیگر"
    ];
    $egucation_levels = [
        "13" => "فارغ التحصیل",
        "13" => "دانشجو",
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
                <!--
                <h3 class="text-center">
                   فیلتر
                </h3>
                <form method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="supporters_id">پشتیبان</label>
                                <select  id="supporters_id" name="supporters_id" class="form-control">
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
                                <select  id="sources_id" name="sources_id" class="form-control">
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
                                <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی" value="{{ isset($name)?$name:'' }}" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="phone">تلفن</label>
                                <input type="number" class="form-control" id="phone" name="phone" placeholder="تلفن"  value="{{ isset($phone)?$phone:'' }}" />
                            </div>
                        </div>
                        <div class="col" style="padding-top: 32px;">
                            <button class="btn btn-success">
                                جستجو
                            </button>
                        </div>
                    </div>
                </form>
                <h3 class="text-center">
                    مرتب سازی
                </h3>
                <div class="row">
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">سطح بندی</a>
                  </div>
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">پیشنهاد فروش</a>
                  </div>
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">محصول</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">سایت</a>
                  </div>
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">تعداد پیشنهاد فروش</a>
                  </div>
                  <div class="col text-center p-1">
                    <a class="btn btn-warning btn-block" href="#">یادآور</a>
                  </div>
                </div>
                <div class="row">
                    <div class="col text-center p-1">
                      <a class="btn btn-warning btn-block" href="#">برچسب اخلاقی</a>
                    </div>
                    <div class="col text-center p-1">
                      <a class="btn btn-warning btn-block" href="#">برچسب ارزیابی</a>
                    </div>
                    <div class="col text-center p-1">
                    </div>
                </div>
                -->

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
                    <!-- <th>#</th> -->
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($students as $index => $item)
                      <tr>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ $index + 1 }}</td>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ $item->id }}</td>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ $item->first_name }}</td>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ $item->last_name }}</td>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ ($item->user)?$item->user->first_name . ' ' . $item->user->last_name:'-' }}</td>
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">{{ ($item->source)?$item->source->name:'-' }}</td>
                        @if($item->studenttags && count($item->studenttags)>0)
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">
                            @for($i = 0; $i < count($item->studenttags);$i++)
                            <span class="alert alert-info p-1">
                                {{ $item->studenttags[$i]->tag->name }}
                            </span>
                            @endfor
                        </td>
                        @else
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();"></td>
                        @endif
                        @if($item->studenttemperatures && count($item->studenttemperatures)>0)
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();">
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
                        <td onclick="$('.morepanel').hide();$('#morepanel-{{ $index }}').toggle();"></td>
                        @endif
                        <td class="text-center">
                            <!-- {{ ($item->supporter)?$item->supporter->first_name . ' ' . $item->supporter->last_name:'-' }} -->
                            <select id="supporters_id_{{ $index }}" class="form-control select2">
                                <option>-</option>
                                @foreach ($supports as $sitem)
                                    @if ($sitem->id==$item->supporters_id)
                                    <option value="{{ $sitem->id }}" selected>
                                    @else
                                    <option value="{{ $sitem->id }}">
                                    @endif
                                    {{ $sitem->first_name }} {{ $sitem->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <br/>
                            <a class="btn btn-success btn-sm" href="#" onclick="return changeSupporter({{ $index }});">
                                ذخیره
                            </a>
                            <br/>
                            <img id="loading-{{ $index }}" src="/dist/img/loading.gif" style="height: 20px;display: none;" />
                        </td>
                        <!--
                        <td>
                            <a class="btn btn-warning" href="#" onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
                                برچسب
                            </a>
                            <a class="btn btn-warning" href="#" onclick="$('#students_index2').val({{ $index }});preloadTemperatureModal();$('#temperature_modal').modal('show'); return false;">
                                داغ/سرد
                            </a>
                            <a class="btn btn-primary" href="{{ route('student_edit', $item->id) }}">
                                ویرایش
                            </a>
                            <a class="btn btn-danger" href="{{ route('student_delete', $item->id) }}">
                                حذف
                            </a>
                        </td>
                        -->
                      </tr>
                      <!--
                      <tr class="morepanel" id="morepanel-{{ $index }}">
                          <td colspan="10">
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
                                       {{ $egucation_levels[$item->] }}
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
                                        {{ $majors[$item->major] }}
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
                                        <a href="#" onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
                                            برچسب روحیات اخلاقی
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="#" onclick="$('#students_index').val({{ $index }});preloadTagModal();$('#tag_modal').modal('show'); return false;">
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
                              </div>
                          </td>
                      </tr>
                      -->
                      @endforeach
                  </tbody>
                  <!--
                  <tfoot>
                  <tr>
                    <th>Rendering engine</th>
                    <th>Browser</th>
                    <th>Platform(s)</th>
                    <th>Engine version</th>
                    <th>CSS grade</th>
                  </tr>
                  </tfoot>
                  -->
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
                    <input type="checkbox" class="collection-checkbox" id="collection_{{ $item->id }}" value="{{ $item->id }}" />
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
    function changeSupporter(studentsIndex){
        if(students[studentsIndex]){
            var students_id = students[studentsIndex].id;
            var supporters_id = $("#supporters_id_" + studentsIndex).val();
            $("#loading-" + studentsIndex).show();
            $.post('{{ route('student_supporter') }}', {
                students_id,
                supporters_id
            }, function(result){
                $("#loading-" + studentsIndex).hide();
                console.log('Result', result);
                if(result.error!=null){
                    alert('خطای بروز رسانی');
                }
            }).fail(function(){
                $("#loading-" + studentsIndex).hide();
                alert('خطای بروز رسانی');
            });
        }
        return false;
    }
    function preloadTagModal(){
        $("input.tag-checkbox").prop('checked', false);
        $("input.collection-checkbox").prop('checked', false);
        var studentsIndex = parseInt($("#students_index").val(), 10);
        if(!isNaN(studentsIndex)){
            if(students[studentsIndex]){
                console.log(students[studentsIndex].studenttags);
                for(studenttag of students[studentsIndex].studenttags){
                    $("#tag_" + studenttag.tags_id).prop("checked", true);
                }
                console.log(students[studentsIndex].studentcollections);
                for(studentcollection of students[studentsIndex].studentcollections){
                    $("#collection_" + studentcollection.collections_id).prop("checked", true);
                }            }
        }
    }
    function preloadTemperatureModal(){
        $("input.tag-checkbox").prop('checked', false);
        var studentsIndex = parseInt($("#students_index2").val(), 10);
        if(!isNaN(studentsIndex)){
            if(students[studentsIndex]){
                console.log(students[studentsIndex].studenttemperatures);
                for(studenttag of students[studentsIndex].studenttemperatures){
                    $("#temperature_" + studenttag.temperatures_id).prop("checked", true);
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
        $("input.collection-checkbox:checked").each(function (id , field){
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
        var studentsIndex = parseInt($("#students_index2").val(), 10);
        if(!isNaN(studentsIndex)){
            if(students[studentsIndex]){
                console.log('selected temperatures', selectedTemperatures);
                $.post('{{ route('student_temperature') }}', {
                    students_id: students[studentsIndex].id,
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

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "language": {
                "paginate": {
                    "previous": "قبل",
                    "next": "بعد"
                },
                "emptyTable":     "داده ای برای نمایش وجود ندارد",
                "info":           "نمایش _START_ تا _END_ از _TOTAL_ داده",
                "infoEmpty":      "نمایش 0 تا 0 از 0 داده",
            }
        });
    });
  </script>
@endsection
