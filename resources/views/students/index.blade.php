@extends('layouts.index')

@section('css')
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ردیف</th>
                    <th>کد</th>
                    <th>نام</th>
                    <th>نام خاانوادگی</th>
                    <th>کاربر ثبت کننده</th>
                    <th>منبع ورودی شماره</th>
                    <th>برچسب</th>
                    <th>داغ/سرد</th>
                    <th>پشتیبان</th>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach ($students as $index => $item)
                      <tr onclick="$('#morepanel-{{ $index }}').toggle();">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->first_name }}</td>
                        <td>{{ $item->last_name }}</td>
                        <td>{{ ($item->user)?$item->user->first_name . ' ' . $item->user->last_name:'-' }}</td>
                        <td>{{ ($item->source)?$item->source->name:'-' }}</td>
                        @if($item->studenttags && count($item->studenttags)>0)
                        <td>
                            @for($i = 0; $i < count($item->studenttags);$i++)
                            <span class="alert alert-info p-1">
                                {{ $item->studenttags[$i]->tag->name }}
                            </span>
                            @endfor
                        </td>
                        @else
                        <td></td>
                        @endif
                        @if($item->studenttemperatures && count($item->studenttemperatures)>0)
                        <td>
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
                        <td></td>
                        @endif
                        <td>{{ ($item->supporter)?$item->supporter->first_name . ' ' . $item->supporter->last_name:'-' }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('student_edit', $item->id) }}">
                                ویرایش
                            </a>
                            <a class="btn btn-danger" href="{{ route('student_delete', $item->id) }}">
                                حذف
                            </a>
                        </td>
                      </tr>
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
                                        {{ $item->egucation_level }}
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
                                        {{ $item->major }}
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
                                    </div>
                                    <div class="col">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="#">
                                            برچسب روحیات اخلاقی
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="#">
                                            برچسب نیازهای دانش آموز
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="#">
                                            گزارش خریدهای قطعی دانش آموز
                                        </a>
                                    </div>
                                </div>
                              </div>
                          </td>
                      </tr>
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
