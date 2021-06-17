@extends('layouts.index')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="/css/dataTableStyleForPurchasesPage.css" rel="stylesheet">
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
        <div class="card card-danger" id="error" style="width: 400px;position: fixed;left: 10px;bottom: 10px;display:none">
            <div class="card-header">
                <h3 class="card-title">خطا</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                            class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="error_card">
                
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card card-success" id="success" style="width: 400px;position: fixed;left: 10px;bottom: 10px;display:none">
            <div class="card-header">
                <h3 class="card-title">موفقیت</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                            class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="success_card">
               
            </div>
            <!-- /.card-body -->
        </div>
      </section>
      <!-- /.content -->
@endsection

@section('js')
<div class="modal" id="edit_site_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">ویرایش</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success" id="successfulSiteEdit" style="display:none"></div>
            <div class="alert alert-danger" id="failedSiteEdit" style="display:none"></div>
            <p id="site_loading">
                <img id="loading" src="/dist/img/loading.gif" style="height: 30px;" />
            </p> 
            <div id="site_div">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="price">مبلغ</label>
                            <input type="number" class="form-control" id="price_site_edit" name="price_site_edit" placeholder="مبلغ" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="price_site_description">توضیحات</label>
                            <textarea rows="4" class="form-control" id="price_site_description" name="price_site_description" placeholder="توضیحات">
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="applySite" onclick="applySiteModal();">اعمال</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel1">انصراف</button>
        </div>
      </div>
    </div>
</div>
<div class="modal" id="edit_manual_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">ویرایش</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success" id="successfulManualEdit" style="display:none"></div>
            <div class="alert alert-danger" id="failedManualEdit" style="display:none"></div>
            <p id="manual_loading">
            <img id="loading" src="/dist/img/loading.gif" style="height: 30px;" />
            </p> 
            <div id="manual_div" style="display: none">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="edit_students_id">دانش آموز</label>
                            <select required class="form-control" id="edit_students_id" name="edit_students_id" style="width: 100%">
                                <option value="" disabled selected>جستجو</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="edit_products_id">محصول</label>
                            <select class="form-control" id="edit_products_id" name="edit_products_id" style="width: 100%">
                            </select>
                        </div>


                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="edit_factor_number">شماره سند</label>
                            <input type="text" class="form-control" id="edit_factor_number" name="edit_factor_number" placeholder="شماره سند" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="edit_manual_price">مبلغ</label>
                            <input type="number" class="form-control" id="edit_manual_price" name="edit_manual_price" placeholder="مبلغ" />
                        </div>
                    </div>
                    @if(!Gate::allows('supervisor') && Gate::allows('parameters'))
                    <div class="col">
                        <div class="form-group">
                            <label for="manual_type">نوع خرید</label>
                            <select class="form-control" id="manual_type" name="manual_type" >
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="manual_description">توضیحات</label>
                            <textarea rows="4" class="form-control" id="manual_description" name="manual_description" placeholder="توضیحات">
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="applyManual" onclick="applyManualModal();">اعمال</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel2">انصراف</button>
        </div>
      </div>
    </div>
</div>
<!-- DataTables -->
<script src="/plugins/select2/js/select2.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables/pagination/listboxWithButtons.js"></script>
<!-- page script -->
<script>
    $('.select2').select2();
    let products = @JSON($products);
    let students = @JSON($students); 
    let appendedOptions = "";
    let appendedOptionsProducts = "";
    let appendedOptionsPurchaseType = "";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function select2_load_remote_data_with_ajax(item, route) {
        // CSRF Token
        $(item).select2({
            ajax: {
                url: route
                ,type: 'post'
                ,dataType: 'json'
                ,delay: 250
                ,data: function(params) {
                    return {
                        _token: CSRF_TOKEN
                        ,search: params.term
                    };
                }
                ,processResults: function(response) {
                    return {
                        results: response
                    };
                }
                ,cache: true
            },
            minimumInputLength: 3
        });
    }
    select2_load_remote_data_with_ajax('#edit_students_id',"{{ route('purchase_get_students') }}");
    select2_load_remote_data_with_ajax('#edit_products_id',"{{ route('purchase_get_products') }}");
    let table = "";
    let lastPage = 1;
    let isSearchCall= false;
    let theId = 0;
    function checkErrorOpen(error, errorMessageId, div, loading, apply){
        if(error) {
            $(errorMessageId).css('display', 'block');
            $(errorMessageId).text(error);
            $(div).css('display', 'none');
            $(loading).css('display', 'none');
            $(apply).css('display', 'none');
        }
    }
    function checkErrorApply(error, errorMessageId){
        if(error){
            $(errorMessageId).css('display', 'block');
            $(errorMessageId).text(error);
            return false;
        }   
    }
    function openManualModal(){
        $('#edit_manual_modal').modal('show'); 
        return false;
    }
    function theSearch(){
        $('#loading').css('display','inline');
        lastPage = table.page();
        isSearchCall = true;
        table.ajax.reload();
        return false;
    }
    $('#edit_site_modal').on('click', function() {
        $('#successfulSiteEdit').css('display', 'none');
        $('#failedSiteEdit').css('display', 'none');
    });
    $('#edit_manual_modal').on('click', function(){
        $('#successfulManualEdit').css('display', 'none');
        $('#failedManualEdit').css('display', 'none');
    });
    function openSiteModal(id){
        $('#site_div').css('display', 'none');
        $('#site_loading').css('display', 'block');
        $('#edit_site_modal').modal('show'); 
        var url = "{{route('purchase_open_site_edit_modal')}}";
        $.ajax({
            data:{
                "id":id,
                _token: CSRF_TOKEN
            },
			url: url,
            type:"POST",
			success: function (result) {
                if(result.error) {
                    checkErrorOpen(result.error,'#failedSiteEdit','#site_div', '#site_loading','#applySite');
                } else {
                    $('#site_div').css('display', 'block');
                    $('#site_loading').css('display', 'none');
                    theId = id;
                    $('#price_site_edit').val(result.data.price);
                    $('#price_site_description').val(result.data.description);
                }	
			},
			error: function () {
               console.log('error');
            }
		});
    }
    function applySiteModal(){
        var url = "{{route('purchase_apply_site_edit_modal')}}";
        $.ajax({
            data:{
                "id":theId,
                "price":$('#price_site_edit').val(),
                "description":$('#price_site_description').val(),
                _token: CSRF_TOKEN
            },
			url: url,
            type:"POST",
			success: function (result) {
                if(result.error){
                    checkErrorOpen(result.error,'#failedSiteEdit');
                } else {
                    $('#successfulSiteEdit').css('display', 'block');
                    $('#successfulSiteEdit').text('با موفقیت به روز شد');
                    theSearch();  
                }             	
			},
			error: function () {
               console.log('error');
            }
		});
    }
    function openManualModal(id){
        $('#manual_div').css('display', 'none');
        $('#manual_loading').css('display', 'block');
        $('#edit_manual_modal').modal('show'); 
        appendedOptions = "";
        appendedOptionsProducts = "";
        var url = "{{route('purchase_open_manual_edit_modal')}}";
        $.ajax({
            data:{
                "id":id,
                _token: CSRF_TOKEN
            },
			url: url,
            type:"POST",
			success: function (result) {
                if(result.error) {
                    checkErrorOpen(result.error,'#failedManualEdit','#manual_div', '#manual_loading','#applyManual');
                } else {
                    $('#manual_div').css('display', 'block');
                    $('#manual_loading').css('display', 'none');
                    theId = id;
                    $('#edit_factor_number').val(result.data.factor_number);
                    let students_id = result.data.students_id;
                    let products_id = result.data.products_id;
                    let description = result.data.description;
                    let types = result.data.types;
                    let type = result.data.type;
                    let price = result.data.price;
                    $('#manual_description').val(description);
                    $('#edit_manual_price').val(price);
                    $('#edit_students_id').val(students_id);
                    $.each(students, function(key,val) {     
                       if(val.id == students_id){
                         appendedOptions += "<option value='"+ val.id +"'>" + val.first_name + ' ' + val.last_name + '[' + val.phone + ']'+ "</option>";        
                       }         
                    });
                    $.each(products, function(key,val) {  
                       if(val.id == products_id) {
                         appendedOptionsProducts += "<option value='"+ val.id +"'>"  + val.name + "</option>";        
                       }  
                    });
                    $.each(types, function(key,val) {    
                       appendedOptionsPurchaseType += "<option value='"+ key +"'"+  (type === key ? 'selected' : '')   +">" + val + "</option>";        
                     });
                     $('#edit_students_id').empty().append(appendedOptions);
                     $('#edit_products_id').empty().append(appendedOptionsProducts);
                     $('#manual_type').empty().append(appendedOptionsPurchaseType);
                }    
            },
			error: function () {
               console.log('error');
            }
		});
    }
    function applyManualModal(){
        var url = "{{route('purchase_apply_manual_edit_modal')}}";
        $.ajax({
            data:{
                "id":theId,
                "price":$('#edit_manual_price').val(),
                "description":$('#manual_description').val(),
                "products_id":$('#edit_products_id').val(),
                "factor_number":$('#edit_factor_number').val(),
                "type":$('#manual_type').val(),
                "students_id":$("#edit_students_id").val(),
                _token: CSRF_TOKEN
            },
			url: url,
            type:"POST",
			success: function (result) {
                if(result.error) {
                    checkErrorOpen(result.error,'#failedManualEdit');
                } else {
                    $('#successfulManualEdit').css('display', 'block');
                    $('#successfulManualEdit').text('با موفقیت به روز شد');
                    theSearch(); 
                }                  	
			},
			error: function () {
               console.log('error');
            }
		});
    }
    function destroy(e){
        if(!confirm('آیا مطمئنید؟')){
            e.preventDefault();
        } 
    }
    function IfConfirmDestroy(id){
        if(confirm('آیا مطمئنید؟')){

           var url = "{{route('purchase_delete')}}";
           $.ajax({
               data:{
                  "id":id,
                  _token: CSRF_TOKEN
               },
               url: url,
               type:"POST",
               success: function (result) {
                 if(result.error) {
                    $('#error').css('display', 'block');
                    $('#error_card').text(result.error);
                 } else {
                    $('#success').css('display', 'block');
                    $('#success_card').text('با موفقیت حذف شد');
                    theSearch(); 
                 }
               },
               error: function () {
                  console.log('error');
               }
            });
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
        "pagingType": "listboxWithButtons",
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
                if(isSearchCall) {
                    table.page(lastPage).draw( 'page' );
                    isSearchCall = false;
                }
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
