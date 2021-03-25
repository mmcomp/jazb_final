@extends('layouts.index')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
@endsection
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
                <h3 class="text-center">
                    فیلتر
                 </h3>
                 <form method="post">
                     @csrf
                     <div class="row">
                         <div class="col">
                             <div class="form-group">
                                 <label for="theId">کد</label>
                                 <input type="text" class="form-control" id="theId" name="theId" placeholder="کد" onkeypress="handle(event)" />
                             </div>
                         </div>
                         <div class="col">
                             <div class="form-group">
                                 <label for="place">محل</label>
                                 <select id="place" name="place" class="form-control" onchange="theChange()">
                                     <option value="">-</option>
                                     @foreach($types as $index => $type)
                                         <option value="{{ $index }}">{{ $type }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="col">
                             <div class="form-group">
                                 <label for="name">نام و نام خانوادگی</label>
                                 <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی" onkeypress="handle(event)" />
                             </div>
                         </div>
                         <div class="col">
                            <div class="form-group">
                                <label for="phone">شماره تلفن</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="شماره تلفن" onkeypress="handle(event)"/>
                            </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="factor_number">شماره فاکتور</label>
                                <input type="number" class="form-control" id="factor_number" name="factor_number" placeholder="شماره فاکتور" onkeypress="handle(event)"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="products_id">محصول</label>
                                <select id="products_id" name="products_id" class="form-control select2" onchange="theChange()">
                                    <option value="">-</option>
                                    @foreach($products as $product )
                                        <option value="{{ $product->id }}">
                                            {{ ($product->collection && $product->collection->parent) ? $product->collection->parent->name : '' }}
                                            {{ ($product->collection && $product->collection->parent) ? '->' : ''}}
                                            {{ $product->collection ? $product->collection->name : ''}}
                                            {{ $product->collection ? '->' : ''}}
                                            {{ $product->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="price">مبلغ</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="مبلغ" onkeypress="handle(event)"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="description">توضیحات</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" onkeypress="handle(event)"/>
                            </div>
                        </div>
                     </div>
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
                            <a class="btn btn-success mt-32" onclick="theSearch()" href="#">
                                جستجو
                            </a>
                            <img id="loading" src="/dist/img/loading.gif" style="height: 20px;display: none;" />
                        </div>
                     </div>
                     {{--  <div class="row">
                        <div class="col">
                            <a class="btn btn-success" onclick="theSearch()" href="#">
                                جستجو
                            </a>
                            <img id="loading" src="/dist/img/loading.gif" style="height: 20px;display: none;" />
                        </div>
                     </div>  --}}
                 </form>
                <table id="example2" class="table table-bordered table-hover mt-2">
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
                    <th>سالن</th>
                    <th>تاریخ</th>
                    <th>سالن</th>
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
<script src="/plugins/select2/js/select2.full.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- page script -->
<script>
    $('select.select2').select2();
    let table = "";
    function theSearch(){
        $('#loading').css('display','inline');
        table.ajax.reload();
        return false;
    }
    function destroy(e){
        if(!confirm('آیا مطمئنید؟')){
            e.preventDefault();
          }
    }
    function theChange(){
        $('#loading').css('display','inline');
        table.ajax.reload();
        return false;
    }
    function handle(e){
        if(e.keyCode === 13){
            e.preventDefault(); // Ensure it is only this code that runs
            $('#loading').css('display','inline');
            table.ajax.reload();
            return false;
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
        columnDefs: [ { orderable: false, targets: [0,10] } ],
        "order": [[1, 'asc']], /// sort columns 1
        processing: true,
        serverSide: true,
        ajax: {
            "type": "POST",
            "url": "{{ route('purchases_post') }}",
            "dataType": "json",
            "contentType": 'application/json; charset=utf-8',

            "data": function (data) {
                data['theId'] = $('#theId').val();
                data['place'] = $('#place').val();
                data['name'] = $("#name").val();
                data['phone'] = $('#phone').val();
                data['factor_number'] = $('#factor_number').val();
                data['products_id'] = $('#products_id').val();
                data['price'] = $('#price').val();
                data['description'] = $('#description').val();
                data['from_date'] = $('#from_date').val();
                data['to_date'] = $('#to_date').val();
                return JSON.stringify(data);
            },
            "complete": function(response) {
                $('#loading').css('display','none');
            }

        },
        columns: [
            { data: 'row'},
            { data: 'id' },
            { data: 'type' },
            { data: 'students_id' },
            { data: 'factor_number' },
            { data: 'products_id'},
            { data: 'price'},
            { data: 'description'},
            { data: 'saloon'},
            { data: 'created_at'},
            { data: 'end'}
        ],
      });
    });
  </script>
@endsection
