@php
$persons = [
    "student"=>"دانش آموز",
    "father"=>"پدر",
    "mother"=>"مادر",
    "other"=>"غیره"
];
@endphp
@extends('layouts.index')

@section('css')
<link rel="stylesheet" href="/plugins/datatables/css/jquery.dataTables.min.css" type="text/css">
<link rel="stylesheet" href="/css/dataTableStyle.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .students, .studenttags{
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
            </div>
            <div class="col-sm-6">
              <h1>
                یادآورها@if($today)ی امروز@endif
              </h1>
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
                  <form id="frm-today" method="post">
                    @csrf
                    <input type="hidden" id="today" name="today" value="true" />
                    <a onclick="$('#today').val('true');$('#frm-today').submit();return false;" class="btn btn-success">
                      امروز
                    </a>
                    <a onclick="$('#today').val('');$('#frm-today').submit();return false;" class="btn btn-warning">
                      همه
                    </a>
                  </form>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>دانش آموز</th>
                    <th>محصول</th>
                    <th>پاسخگو</th>
                    <th>نتیجه</th>
                    <th>یادآور</th>
                    <th>پاسخگو بعد</th>
                    <th>توضیحات</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      {{--  @foreach ($calls as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ ($item->student)?$item->student->first_name . ' ' . $item->student->last_name:'-' }}</td>
                        <td>{{ ($item->product)?(($item->product->parents!='-')?$item->product->parents . '->':'') . $item->product->name:'-' }}</td>
                        <td>{{ $persons[$item->replier] }}</td>
                        <td>{{ ($item->callresult)?$item->callresult->title:'-' }}</td>
                        <td>{{ ($item->next_call)?jdate($item->next_call)->format("Y/m/d"):'-' }}</td>
                        <td>{{ ($item->next_to_call)?$persons[$item->next_to_call]:'-' }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                          <a class="btn btn-danger" href="{{ route('reminder_delete', ['id'=>$item->id]) }}">
                              حذف
                          </a>
                          <form method="get" action="{{ route('supporter_students') }}" >
                            <input type="hidden" name="students_id" value="{{$item->students_id}}" />
                            <input type="hidden" name="calls_id" value="{{$item->id}}" />
                            <button class="btn btn-primary">
                              تماس
                            </button>
                          </form>
                        </td>
                      </tr>
                      @endforeach  --}}
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
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- page script -->
<script>
    var table;
    function showStudents(index){
        // $(".students").hide();
        $("#students-" + index).toggle();

        return false;
    }
    function showStudentTags(index){
        // $(".students").hide();
        $("#studenttags-" + index).toggle();

        return false;
    }
    $(".btn-danger").click(function(e){
        alert('hello');
        if(!confirm('آیا مطمئنید؟')){
          e.preventDefault();
        }
    });
    $(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      table = $('#example2').DataTable({
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
        },
        "columnDefs": [   ////define columns
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    },
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 9
                    },
            ],
        "order": [[1, 'asc']], /// sort columns 2
            serverSide: true,
            processing: true,
            ajax: {
                "type": "POST",
                "url": "{{ route('reminders') }}",
                "dataType": "json",
                "contentType": 'application/json; charset=utf-8',

                "data": function (data) {
                    {{--  data['supporters_id'] = $("#supporters_id").val();
                    data['sources_id'] = $("#sources_id").val();
                    data['cities_id'] = $("#cities_id").val();
                    data['egucation_level'] = $("#egucation_level").val();
                    data['major'] = $("#major").val();
                    data['school'] = $("#school").val();
                    data['name'] = $("#name").val();
                    data['phone'] = $("#phone").val();  --}}

                    return JSON.stringify(data);
                },
                "complete": function(response) {
                    $('#example2_paginate').removeClass('dataTables_paginate');
                   // console.log(response);
                }

            },
            columns: [
                { data: null},
                { data: 'id' },
                { data: 'student' },
                { data: 'product' },
                { data: 'replier' },
                { data: 'callresult'},
                { data: 'next_call'},
                { data: 'next_to_call'},
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


    });
  </script>
@endsection
