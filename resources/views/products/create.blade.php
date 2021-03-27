@extends('layouts.index')

@section('css')
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>محصول</h1>
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
                            <label for="name">نام</label>
                            @if (isset($product) && isset($product->id))
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ $product->name }}" />
                            @else
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="price">قیمت</label>
                            @if (isset($product) && isset($product->id))
                            <input type="number" class="form-control" id="price" name="price" placeholder="قیمت" value="{{ $product->price }}" />
                            @else
                            <input type="number" class="form-control" id="price" name="price" placeholder="قیمت"  />
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="parent_id">دسته</label>
                            <select class="form-control select2" id="collections_id" name="collections_id" >
                                <option value="0"> - </option>
                                @foreach ($collections as $item)
                                    @if (isset($product) && isset($product->id) && $product->collections_id == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
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
   $('select.select2').select2();
</script>
@endsection
