@php
// dd($request);
$persons = [
    "student"=>"دانش آموز",
    "father"=>"پدر",
    "mother"=>"مادر",
    "other"=>"غیره"
];
@endphp
@extends('layouts.index')

@section('css')
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
              <h1>
                  تماس های
                  توسط
                  {{ $supporter->first_name }}
                  {{ $supporter->last_name }}
              </h1>
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>دانش آموز</th>
                    <th>محصول</th>
                    <th>اطلاع رسانی</th>
                    <th>پاسخگو</th>
                    <th>نتیجه</th>
                    <th>یادآور</th>
                    <th>پاسخگو بعد</th>
                    <th>تاریخ تماس</th>
                    <th>توضیحات</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($calls as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->student->first_name }} {{ $item->student->last_name }}</td>
                        <td>{{ ($item->product)?(($item->product->parents!='-')?$item->product->parents . '->':'') . $item->product->name:'-' }}</td>
                        <td>{{ ($item->notice)? $item->notice->name:'-' }}</td>
                        <td>{{ $persons[$item->replier] }}</td>
                        <td>{{ ($item->callresult)?$item->callresult->title:'-' }}</td>
                        <td>{{ ($item->next_call)?jdate($item->next_call)->format("Y/m/d"):'-' }}</td>
                        <td>{{ ($item->next_to_call)?$persons[$item->next_to_call]:'-' }}</td>
                        <td>{{($item->created_at)?jdate($item->created_at)->format("Y/m/d"):jdate()->format("Y/m/d")}}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <form method="POST" action="{{ route('user_supporter_acall') }}">
                                @csrf
                                <input type="hidden" name="from_date" value="{{ ($request['from_date'])?$request['from_date']:'' }}">
                                <input type="hidden" name="to_date" value="{{ ($request['to_date'])?$request['to_date']:'' }}">
                                <input type="hidden" name="products_id" value="{{ ($request['products_id'])?$request['products_id']:'' }}">
                                <input type="hidden" name="notices_id" value="{{ ($request['notices_id'])?$request['notices_id']:'' }}">
                                <input type="hidden" name="replier_id" value="{{ ($request['replier_id'])?$request['replier_id']:'' }}">
                                <input type="hidden" name="sources_id" value="{{ ($request['sources_id'])?$request['sources_id']:'' }}">
                                <input type="hidden" name="call_id" value="{{ $item->id }}">
                                <input type="hidden" name="id" value="{{ $item->users_id }}">
                                <button class="btn btn-danger">
                                    حذف
                                </button>
                            </form>
                        </td>
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
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- page script -->
<script>
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
    $(function () {
    //   $("#example1").DataTable();
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

      $(".btn-danger").click(function(e){
          if(!confirm('آیا مطمئنید؟')){
            e.preventDefault();
          }
      });
    });
  </script>
@endsection
