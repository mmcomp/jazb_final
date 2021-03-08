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
              @if(isset($c))
              <h1>ویرایش ارتباط</h1>
              @else
              <h1>ایجاد ارتباط</h1>
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
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="products_id">محصول</label>
                                <select class="form-control select2" id="products_id" name="products_id" >
                                    @foreach ($products as $item)
                                        <option value=""></option>
                                        @if (isset($c) && $c->products_id==$item->id)
                                        <option value="{{ $item->id }}" selected>{{ (($item->parents!='')?$item->parents . '->':'') . $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}">{{ (($item->parents!='')?$item->parents . '->':'') . $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="commission">کمیسیون</label>
                                @if(isset($c))
                                <input type="number" class="form-control" id="commission" name="commission" placeholder="کمیسیون" value="{{ $c->commission ? $c->commission : $c->user->default_commision }}" />
                                @else
                                <input type="number" class="form-control" id="commission" name="commission" placeholder="کمیسیون"  />
                                @endif
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn {{ isset($c)?  "btn-warning text-white" : "btn-primary" }}">
                            {{ isset($c) ? "ویرایش" : "ذخیره" }}
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
