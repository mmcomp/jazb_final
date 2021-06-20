@extends('layouts.index')
@section('css')
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="/dist/css/select2-style.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1> ثبت خرید از اکسل</h1>
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
                {{-- @if ($msg_success!=null && count($fails)>0)
                <div class="text-center">
                    <h3>
                       {{ count($fails) }} شماره به علت تکراری بودن ثبت نشده است.
                    </h3>
                    {{-- @foreach ($fails as $item)
                    <div>
                        {{ $item }}
                    </div>
                    @endforeach --}}
                    {{-- @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                @endif --}}
                </div>
                {{-- @endif --}}
                <form method="POST" enctype="multipart/form-data" action="{{ route('pur_assign_excel_post')}}">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="products">محصول</label>
                            <select  id="products" name="products[]" class="form-control select2 " multiple required>
                                <option value="0" selected>محصول</option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->id }}" >
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="attachment">فایل CSV <a target="_blank" href="/phones.csv">مثال</a></label>
                            <label for="attachment"> یا فایل xlsx <a target="_blank" href="/phones.xlsx">مثال</a></label>
                            <label for="attachment" class="text-sm text-danger">شماره تلفن ها باید حتما انگلیسی و بدون صفر اول وارد شوند.</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary" id="btn">
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
<script src="/dist/js/purchase-excel.js"></script>
@endsection