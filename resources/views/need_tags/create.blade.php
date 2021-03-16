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
                <h1>برچسب نیازسنجی</h1>
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
                                    <select class="form-control" id="products_id" name="products_id">
                                        <option value="0"> - </option>
                                        @foreach ($products as $item)
                                        @if (isset($tag) && isset($tag->id) && $tag->products_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="parent1">برچسب فرعی 1</label>
                                    <select class="form-control" id="need_parent2" name="need_parent2">
                                        <option value="0"> - </option>
                                        @foreach ($tagParentTwos as $item)
                                        @if (isset($tag) && isset($tag->id) && $tag->need_parent2 == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="parent1">برچسب فرعی 3</label>
                                    <select class="form-control" id="need_parent4" name="need_parent4">
                                        <option value="0"> - </option>
                                        @foreach ($tagParentFours as $item)
                                        @if (isset($tag) && isset($tag->id) && $tag->need_parent4 == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="parent1">برچسب اصلی</label>
                                    <select class="form-control" id="need_parent1" name="need_parent1">
                                        <option value="0"> - </option>
                                        @foreach ($tagParentOnes as $item)
                                        @if (isset($tag) && isset($tag->id) && $tag->need_parent1 == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="parent1">برچسب فرعی 2</label>
                                    <select class="form-control" id="need_parent3" name="need_parent3">
                                        <option value="0"> - </option>
                                        @foreach ($tagParentThrees as $item)
                                        @if (isset($tag) && isset($tag->id) && $tag->need_parent3 == $item->id)
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
<script src="/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
    let route = "";
    function select2_load_remote_data_with_ajax(item) {
        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        switch (item) {
            case "#products_id":
              route = "{{ route('product_ajax_get') }}";
              break;
            case '#need_parent1':
              route = "{{ route('needtag1_ajax_get')}}";
              break;
            case '#need_parent2':
              route = "{{ route('needtag2_ajax_get')}}";
              break;
            case '#need_parent3':
              route = "{{ route('needtag3_ajax_get')}}";
              break;
            case '#need_parent4':
              route = "{{ route('needtag4_ajax_get')}}";
              break;
        }
        $(item).select2({
            ajax: {
                url: route
                , type: 'post'
                , dataType: 'json'
                , delay: 250
                , data: function(params) {
                    return {
                        _token: CSRF_TOKEN
                        , search: params.term
                    };
                }
                , processResults: function(response) {
                    return {
                        results: response
                    };
                }
                , cache: true
            }
            ,
            minimumInputLength: 3
        });
    }
    select2_load_remote_data_with_ajax('#products_id');
    select2_load_remote_data_with_ajax('#need_parent1');
    select2_load_remote_data_with_ajax('#need_parent2');
    select2_load_remote_data_with_ajax('#need_parent3');
    select2_load_remote_data_with_ajax('#need_parent4');
</script>
@endsection
