@extends('layouts.index')
@section('css')
<style>
 .list_style_type_none{
     list-style-type: none;
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
                  پرداخت های
                  @if($appMergeStudent)
                  {{ $appMergeStudent->mainstudent->first_name }} {{ $appMergeStudent->mainstudent->last_name }}
                  [{{ $appMergeStudent->mainstudent->phone }}]
                  @else
                  {{ $student->first_name}} {{$student->last_name}}
                  [{{$student->phone}}]
                  @endif
              </h1>
              <br>
                  @if($appMergeStudent)
                  <p class="text-info">افراد مرتبط</p>
                  <ul class="list_style_type_none">
                      <li>
                        {{$appMergeStudent->auxilaryStudent ? $appMergeStudent->auxilaryStudent->first_name : ''}}
                        {{$appMergeStudent->auxilaryStudent ? $appMergeStudent->auxilaryStudent->last_name : ''}}
                        {{$appMergeStudent->auxilaryStudent ? '['.$appMergeStudent->auxilaryStudent->phone.']' : ''}}
                      </li>
                      <li>
                        {{$appMergeStudent->secondAuxilaryStudent ? $appMergeStudent->secondAuxilaryStudent->first_name : ''}}
                        {{$appMergeStudent->secondAuxilaryStudent ? $appMergeStudent->secondAuxilaryStudent->last_name : ''}}
                        {{$appMergeStudent->secondAuxilaryStudent ? '['.$appMergeStudent->secondAuxilaryStudent->phone.']' : ''}}
                      </li>
                      <li>
                        {{$appMergeStudent->thirdAuxilaryStudent ? $appMergeStudent->thirdAuxilaryStudent->first_name : ''}}
                        {{$appMergeStudent->thirdAuxilaryStudent ? $appMergeStudent->thirdAuxilaryStudent->last_name : ''}}
                        {{$appMergeStudent->thirdAuxilaryStudent ? '['.$appMergeStudent->thirdAuxilaryStudent->phone.']' : ''}}
                      </li>
                  </ul>
                  @else
                  <p class="text-danger">هیچ فرد مرتبطی با این دانش آموز وجود ندارد.</p>
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>شماره فاکتور</th>
                    <th>محصول</th>
                    <th>مبلغ</th>
                    <th>توضیحات</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($purchases as $index => $item)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->factor_number }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
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
