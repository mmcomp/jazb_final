@extends('layouts.index')

@section('css')
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>پرداخت</h1>
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
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                @csrf
                @if($purchase->type == "manual")
                <div class="row">
                    <div class="col">

                        <div class="form-group">
                            <label for="students_id">دانش آموز</label>
                            <select required class="form-control select2" id="students_id" name="students_id">
                                <option value="" disabled selected>جستجو</option>
                                @foreach ($students as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $purchase->students_id ? "selected" : ''}}>{{ $item->first_name }} {{ $item->last_name }} [{{ $item->phone }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col">
                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ $purchase->description }}" />
                        </div>


                    </div> --}}
                </div>
                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="products_id">محصول</label>
                            <select class="form-control select2" id="products_id" name="products_id" >
                                @foreach ($products as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $purchase->products_id ? "selected" : ''}}>{{ (($item->parents!='')?$item->parents . '->':'') . $item->name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="factor_number">شماره سند</label>
                            <input type="text" class="form-control" id="factor_number" name="factor_number" placeholder="شماره سند"  value="{{ $purchase->factor_number }}" />
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="price">مبلغ</label>
                            <input type="number" class="form-control" id="price" name="price" placeholder="مبلغ"  value="{{ $purchase->price }}" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ $purchase->description }}" />
                        </div>
                    </div>
                    
                    @if(!Gate::allows('supervisor') && Gate::allows('parameters') && $purchase->type != "site_successed")
                    <div class="col">
                        <div class="form-group">
                            <label for="type">نوع خرید</label>
                            <select class="form-control" id="type" name="type" >
                               @foreach($types as $index => $type)
                                 <option value="{{ $index }}" {{ $purchase->type == $index ? "selected" : '' }}>{{ $type }}</option>
                               @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-warning text-white">
                            ویرایش
                        </button>
                    </div>
                </div>
                </form>
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
<!-- Select2 -->
<script src="/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function(){
        $('select.select2').select2();

    });
</script>
@endsection
