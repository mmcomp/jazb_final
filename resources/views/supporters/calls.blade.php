@extends('layouts.index')

@php
$persons = [
    "student"=>"دانش آموز",
    "father"=>"پدر",
    "mother"=>"مادر",
    "other"=>"غیره"
];
@endphp

@section('css')
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
              @if($isSingle)
              <h1>تماس ها</h1>
              @else
              <h1>تماس پشتیبان ها</h1>
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
        <form method="POST">
            @csrf
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="from_date">از تاریخ</label>
                        <input type="text" id="from_date" name="from_date" class="form-control pdate" value="{{ ($from_date)?jdate($from_date)->format("Y/m/d"):jdate()->format("Y/m/d") }}" />
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="to_date">تا تاریخ</label>
                        <input type="text" id="to_date" name="to_date" class="form-control pdate" value="{{ ($to_date)?jdate($to_date)->format("Y/m/d"):jdate()->format("Y/m/d") }}" />
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="products_id">محصول</label>
                        <select id="products_id" name="products_id" class="form-control" >
                            <option value="">همه</option>
                            @foreach ($products as $item)
                                @if($products_id && $products_id == $item->id)
                                <option selected value="{{ $item->id }}">
                                    {{ ($item->parents!='-')?$item->parents . '->':'' }}{{ $item->name }}
                                </option>
                                @else
                                <option value="{{ $item->id }}">
                                    {{ ($item->parents!='-')?$item->parents . '->':'' }}{{ $item->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="notices_id">اطلاع رسانی</label>
                        <select id="notices_id" name="notices_id" class="form-control" >
                            <option value="">همه</option>
                            @foreach ($notices as $item)
                                @if($notices_id && $notices_id == $item->id)
                                <option selected value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @else
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(!$isSingle)
                <div class="col-3">
                    <div class="form-group">
                        <label for="supporters_id">پشتیبان</label>
                        <select id="supporters_id" name="supporters_id" class="form-control" >
                            <option value="">همه</option>
                            @foreach ($supporters as $item)
                                @if($supporters_id && $supporters_id == $item->id)
                                <option selected value="{{ $item->id }}">
                                    {{ $item->first_name }} {{ $item->last_name }}
                                </option>
                                @else
                                <option value="{{ $item->id }}">
                                    {{ $item->first_name }} {{ $item->last_name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-3">
                    <div class="form-group">
                        <label for="replier_id">پاسخ دهنده</label>
                        <select id="replier_id" name="replier_id" class="form-control" >
                            <option value="">همه</option>
                            @foreach ($persons as $key=>$item)
                                @if($replier_id && $replier_id == $key)
                                <option selected value="{{ $key }}">
                                    {{ $item }}
                                </option>
                                @else
                                <option value="{{ $key }}">
                                    {{ $item }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="to_date">منبع ورودی</label>
                        <select id="sources_id" name="sources_id" class="form-control" >
                            <option value="">همه</option>
                            @foreach ($sources as $item)
                                @if($sources_id && $sources_id == $item->id)
                                <option selected value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @else
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="to_date">&nbsp;</label>
                        <input type="submit" class="btn btn-success form-control" value="جستجو" />
                    </div>
                </div>
            </div>
        </form>
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
                    @if(!$isSingle)
                    <th>کد</th>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    @endif
                    <th>تماس</th>
                    @foreach ($callResults as $item)
                        <th>
                            {{ $item->title }}
                        </th>
                    @endforeach
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
                        @if(!$isSingle)
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->first_name }}</td>
                        <td>{{ $item->last_name }}</td>
                        @endif
                        <td>
                            <form method="POST" action="{{ route('user_supporter_acall') }}" >
                                @csrf
                                <input type="hidden" name="from_date" value="{{ ($from_date)?$from_date:'' }}" />
                                <input type="hidden" name="to_date" value="{{ ($to_date)?$to_date:'' }}" />
                                <input type="hidden" name="products_id" value="{{ ($products_id)?$products_id:'' }}" />
                                <input type="hidden" name="notices_id" value="{{ ($notices_id)?$notices_id:'' }}" />
                                <input type="hidden" name="replier_id" value="{{ ($replier_id)?$replier_id:'' }}" />
                                <input type="hidden" name="sources_id" value="{{ ($sources_id)?$sources_id:'' }}" />
                                <input type="hidden" name="id" value="{{ $item->id }}" />
                                <button class="btn btn-link">
                                    {{ $item->callCount }}
                                </button>
                            </form>
                        </td>
                        @if($item->supporterCallResults)
                        @foreach ($item->supporterCallResults as $sitem)
                            <td>
                                {{ (isset($sitem['count']))?$sitem['count']:'0' }}
                            </td>
                        @endforeach
                        @endif
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
    function changePass(user_id) {
        // alert(user_id);
        var password = prompt('لطفا رمز عبور جدید را وارد کنید:');
        if(!confirm(`آیا رمز عبور به ${password} تغییر یابد؟`)){
            return false;
        }

        $.post('{{ route("user_supporter_changepass") }}', {
            user_id,
            password,
            _token: "{{ csrf_token() }}"
        }, function(res){
            console.log(res);
            if(res.error==null)
                window.location.reload();
        });
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
