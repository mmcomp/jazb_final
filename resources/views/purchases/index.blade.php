@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>پرداخت ها</h1>
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
                    <a class="btn btn-success" href="{{ route('purchase_create') }}">پرداخت جدید</a>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>محل</th>
                    <th>نام و نام خانوادگی</th>
                    <th>شماره فاکتور</th>
                    <th>محصول</th>
                    <th>مبلغ</th>
                    <th>توضیحات</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      @if($purchases)
                      @foreach ($purchases as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        {{-- <td>{{ ($item->type == 'manual')?'حضوری':'سایت' }}</td> --}}
                        <td>
                            @if($item->type == 'manual')
                            حضوری
                            @elseif($item->type == 'site_successed')
                            سایت
                            @elseif($item->type == 'site_failed')
                            انصرافی
                            @endif
                        </td>
                        <td>
                            {{ $item->student ? $item->student->first_name. ' '. $item->student->last_name.' ['.$item->student->phone.']' : '-' }}
                        </td>
                        <td>
                            {{ $item->factor_number }}
                        </td>
                        <td>
                            {{( $item->product && $item->product->collection && $item->product->collection->parent) ? $item->product->collection->parent->name : ''}}
                            {{( $item->product && $item->product->collection && $item->product->collection->parent) ? '->' : ''}}
                            {{( $item->product && $item->product->collection)?$item->product->collection->name : ''}}
                            {{ $item->product && $item->product->collection ? '->' : ''}}
                            {{ $item->product ? $item->product->name : '-' }}
                        </td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('purchase_edit', $item->id) }}">
                   `             ویرایش
                            </a>
                            <a class="btn btn-danger" href="{{ route('purchase_delete', $item->id) }}">
                                حذف
                            </a>
                        </td>
                      </tr>
                      @endforeach
                      @endif
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
    $(".btn-danger").click(function(e){
        if(!confirm('آیا مطمئنید؟')){
          e.preventDefault();
        }
    });
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
