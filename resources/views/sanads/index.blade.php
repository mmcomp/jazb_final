@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>سند</h1>
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
                    <a class="btn btn-success" href="{{ route('sanad_create') }}">سند جدید</a>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>شماره سند </th>
                    <th> تاریخ  سند </th>
                    <th>شرح</th>
                    <th>بدهکار</th>
                    <th>بستانکار</th>
                   <!-- <th>مانده</th> -->
                    <!-- <th>کد</th> -->
                    <th>پشتیبان</th>
                    <th>قیمت کل</th>
                    <th>سهم پشتیبان(درصد)</th>
                   
                   <!-- <th>نوع</th> -->
                    <th>ویرایش</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($sanads as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->number}} </td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format('Y-m-d')  }}</td>
                        <td>{{ $item->description }}</td>
                        <!-- <td>{{ $item->id }}</td> -->
                         <td>{{ $item->type > 0 ? $item->total_cost : ''}}</td> 
                        <td>{{ $item->type < 0 ? $item->total_cost : '' }}</td> 
                        <td>{{ $item->supporter->first_name. ' ' . $item->supporter->last_name }}</td>
                        <td>{{ $item->total }}</td>
                        <td>{{ ceil($item->total * $item->supporter_percent / 100) }}</td>
                       
                        <!-- <td>{{ $item->type && $item->type < 0 ? 'بدهکار' : 'بستانکار' }}</td> -->
                        <td> <a class="btn btn-info" href="{{ route('sanad_edit',$item->id) }}"> ویرایش  </a> </td>
                        <!-- <td>{{ $item->name }}</td>
                        <td></td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('school_edit', $item->id) }}">
                                ویرایش
                            </a>
                            <a class="btn btn-danger" href="{{ route('school_delete', $item->id) }}">
                                حذف
                            </a>
                        </td> -->
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
        },
        "columnDefs": [   ////define column 1 and 3
        {
            "searchable": false,
            "orderable": false,
            "targets": [0,3]
        },
        { "type": "pstring", "targets": 2 }

        ],
        "order":[1,'asc']
      });

      $(".btn-danger").click(function(e){
          if(!confirm('آیا مطمئنید؟')){
            e.preventDefault();
          }
      });
    });
  </script>
@endsection
