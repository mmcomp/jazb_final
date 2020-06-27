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
              <h1>پشتیبان ها</h1>
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
                    <!-- <a class="btn btn-success" href="{{ route('user_create') }}">پشتیبان جدید</a> -->
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    <th>دانش آموزان</th>
                    <th>محل کار</th>
                    <th>فروش</th>
                    <th>برچسب ها</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($supporters as $index => $item)
                      @php
                        $purchaseCount = 0;
                        $tags = [];
                      @endphp
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->first_name }}</td>
                        <td>{{ $item->last_name }}</td>
                        @if($item->students && count($item->students)>0)
                        <td>
                            <a onclick="return showStudents({{ $index }});" href="#">{{ count($item->students) }}</a>
                            <div class="students" id="students-{{ $index }}">
                                @foreach ($item->students as $sitem)
                                    <span >
                                        {{ $sitem->first_name }} {{ $sitem->last_name }} [{{ $sitem->phone }}]
                                    </span><br/>
                                    @php
                                        $purchaseCount += count($sitem->purchases);
                                    @endphp
                                @endforeach
                            </div>
                        </td>
                        @else
                        <td>0</td>
                        @endif
                        <td>{{ $item->work_address }}</td>
                        <td>{{ $purchaseCount }}</td>
                        @if($item->students && count($item->students)>0)
                        <td>
                            <a onclick="return showStudentTags({{ $index }});" href="#">برچسب ها</a>
                            <div class="studenttags" id="studenttags-{{ $index }}">
                                @foreach ($item->students as $student)
                                @foreach ($student->studenttags as $sitem)
                                @if(!isset($tags[$sitem->tags_id]))
                                <span >
                                    {{ $sitem->tag->name }}
                                </span><br/>
                                @php
                                    $tags[] = $sitem->tags_id;
                                @endphp
                                @endif
                                @endforeach
                                @endforeach
                            </div>
                        </td>
                        @else
                        <td></td>
                        @endif
                        <td>
                            <!--
                            <a class="btn btn-primary" href="{{ route('user_edit', $item->id) }}">
                                ویرایش
                            </a>
                            <a class="btn btn-danger" href="{{ route('user_delete', $item->id) }}">
                                حذف
                            </a>
                            -->
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
