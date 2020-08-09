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
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="students_id">دانش آموز</label>
                            <select required class="form-control select2" id="students_id" name="students_id">
                                <option value="" disabled selected>جستجو</option>
                                @foreach ($students as $item)
                                    @if (isset($purchase) && isset($purchase->id) && $purchase->students_id==$item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->first_name }} {{ $item->last_name }} [{{ $item->phone }}]</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} [{{ $item->phone }}]</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            @if (isset($purchase) && isset($purchase->id))
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ $purchase->description }}" />
                            @else
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="factor_number">شماره سند</label>
                            @if (isset($purchase) && isset($purchase->id))
                            <input type="text" class="form-control" id="factor_number" name="factor_number" placeholder="شماره سند"  value="{{ $purchase->factor_number }}" />
                            @else
                            <input type="text" class="form-control" id="factor_number" name="factor_number" placeholder="شماره سند"  />
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="products_id">محصول</label>
                            <select class="form-control select2" id="products_id" name="products_id" >
                                @foreach ($products as $item)
                                    @if (isset($purchase) && isset($purchase->id) && $purchase->products_id==$item->id)
                                    <option value="{{ $item->id }}" selected>{{ (($item->parents!='-')?$item->parents . '->':'') . $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ (($item->parents!='-')?$item->parents . '->':'') . $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">مبلغ</label>
                            @if (isset($purchase) && isset($purchase->id))
                            <input type="number" class="form-control" id="price" name="price" placeholder="مبلغ"  value="{{ $purchase->price }}" />
                            @else
                            <input type="number" class="form-control" id="price" name="price" placeholder="مبلغ"  />
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary">
                            ذخیره
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
