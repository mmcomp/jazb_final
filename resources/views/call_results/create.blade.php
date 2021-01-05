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
              <h1>نتیجه تماس</h1>
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
                            <label for="title">موضوع</label>
                            @if (isset($callResult) && isset($callResult->id))
                            <input type="text" class="form-control" id="title" name="title" placeholder="موضوع" value="{{ $callResult->title }}" />
                            @else
                            <input type="text" class="form-control" id="title" name="title" placeholder="موضوع"  />
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            @if (isset($callResult) && isset($callResult->id))
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ $callResult->description }}" />
                            @else
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات"  />
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="no_call">بدون یادآور</label>
                            @if (isset($callResult) && isset($callResult->id) && $callResult->no_call)
                            <input type="checkbox" id="no_call" name="no_call" checked/>
                            @else
                            <input type="checkbox" id="no_call" name="no_call" />
                            @endif
                        <!-- </div>

                        <div class="form-group"> -->
                            <label for="no_answer">بدون پاسخ</label>
                            @if (isset($callResult) && isset($callResult->id) && $callResult->no_answer)
                            <input type="checkbox" id="no_answer" name="no_answer" checked/>
                            @else
                            <input type="checkbox" id="no_answer" name="no_answer" />
                            @endif
                        </div>
                    </div>
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
