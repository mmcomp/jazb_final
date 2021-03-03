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
              <h1 id="today_header">
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
                  {{--  <form id="frm-today" method="post" action="{{route('reminders_post')}}">  --}}
                     {{--  @csrf  --}}
                    {{--  @if($date == null)  --}}
                    <input type="hidden" id="today" name="today" value="{{ $today ? 'true' : 'false'}}" />
                    <button onclick="todayFunc()" class="btn btn-success">
                      امروز
                    </button>
                    <button onclick="allFunc()" class="btn btn-warning">
                      همه
                    </button>
                    {{--  @endif  --}}

                  {{--  </form>  --}}
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
    var date = "{{ $date }}";
    var currentRoute = '{{Route::current()->getName()}}';

    function showStudents(index){
        $("#students-" + index).toggle();

        return false;
    }
    function showStudentTags(index){
        $("#studenttags-" + index).toggle();

        return false;
    }
    function todayFunc(){
        $('#today_header').text('یادآورهای امروز');
        $('#today').val('true');
        table.ajax.reload();
    }
    function allFunc(){
        $('#today_header').text('یادآورها');
        $('#today').val('false');
        window.location.replace('{{ route("reminders_post",["date" => null])}}')
    }

    function destroy(e){
        if(!confirm('آیا مطمئنید؟')){
          e.preventDefault();
        }
    }

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
                "url": "{{ route('reminders_post') }}",
                "dataType": "json",
                "contentType": 'application/json; charset=utf-8',

                "data": function (data) {
                    data['today'] = date == "today" ? "true" : $('#today').val();
                    console.log(data['today']);
                    return JSON.stringify(data);
                },
                "complete": function(response) {
                    $('#example2_paginate').removeClass('dataTables_paginate');
                }

            },
            columns: [
                { data: null},
                { data: 'id' },
                { data: 'students_id' },
                { data: 'products_id' },
                { data: 'replier' },
                { data: 'call_results_id'},
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
