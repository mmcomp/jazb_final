@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>صندوق پیام</h1>
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
                    @if($user)
                    <a class="btn btn-success" href="{{ route('message_user_create', $user->id) }}">پیام جدید</a>
                    @else
                    <a class="btn btn-success" href="{{ route('message_create') }}">پیام جدید</a>
                    @endif
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>ایجاد کننده</th>
                    <th>پیام</th>
                    <th>ضمیمه</th>
                    <th>گیرنده ها</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($messages as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ ($item->user)?$item->user->first_name . ' ' . $item->user->last_name:'-' }}</td>
                        <td>{{ $item->message }}</td>
                        @if($item->attachment)
                        <td><a target="_blank" href="/uploads/{{ $item->attachment }}">ضمیمه</a></td>
                        @else
                        <td></td>
                        @endif
                        <td>
                            @foreach ($item->flows as $fitem)
                                <span class="alert alert-primary">
                                {{ $fitem->user->first_name }} {{ $fitem->user->last_name }} {{ jdate(strtotime($fitem->created_at))->format("Y/m/d") }}
                                </span>
                            @endforeach
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
