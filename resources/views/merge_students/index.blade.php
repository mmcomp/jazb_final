@extends('layouts.index')
@section('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>همگام سازی دانش آموزان</h1>
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
                    <a class="btn btn-success" href="{{ route('merge_students_create') }}">همگام سازی جدید</a>
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
                               <label for="name">نام و نام خانوادگی</label>
                               <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی" value=""/>
                           </div>
                       </div>
                       <div class="col">
                           <div class="form-group">
                               <label for="phone">تلفن</label>
                               <input type="number" class="form-control" id="phone" name="phone" placeholder="تلفن"  value=""/>
                           </div>
                       </div>
                       <div class="col" style="padding-top: 32px;">
                           <button class="btn btn-success" onclick="theSearch(this)" id="theBtn">
                            جستجو
                            </button>
                            <img id="loading" src="/dist/img/loading.gif" style="height: 20px;display: none;" />
                        </div> 
                   </div>
                   {{-- <div class="row">
                      

                   </div>         --}}
               </form>
                <table id="example" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th> دانش آموز اصلی</th>
                    <th>فرعی ۱</th>
                    <th>فرعی ۲</th>
                    <th>فرعی ۳</th>
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
    let table = "";
    function theSearch(myself){
        $(myself).prop('disabled',true);
        $('#loading').css('display','inline');
        table.ajax.reload();
        return false;
    }
    $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      table = $('#example').DataTable({
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
        "columnDefs": [   ////define column 1 and 5
        {
            "searchable": false,
            "orderable": false,
            "targets": [0,5]
        },
        //{ "type": "pstring", "targets": [2,3,4] }
        ],

        "order": [[1, 'asc']], /// sort columns 2
        serverSide: true,
            processing: true,
            ajax: {
                "type": "POST",
                "url": "{{ route('merge_students_index_post') }}",
                "dataType": "json",
                "contentType": 'application/json; charset=utf-8',

                "data": function (data) {
                    data['name'] = $("#name").val();
                    data['phone'] = $('#phone').val();
                    return JSON.stringify(data);
                },
                "complete": function(response) {
                    $('#loading').css('display','none');
                    $('#theBtn').prop('disabled',false);
                }
            },
            columns: [
                { data: 'row'},
                { data: 'main_students_id' },
                { data: 'auxilary_students_id' },
                { data: 'second_auxilary_students_id' },
                { data: 'third_auxilary_students_id' },
                { data : 'end'}
            ],
      });

      $(".btn-danger").click(function(e){
          if(!confirm('آیا مطمئنید؟')){
            e.preventDefault();
          }
      });
    });
  </script>
@endsection
