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
              <h1>داغ/سرد</h1>
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
                            @if (isset($temperature) && isset($temperature->id))
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ $temperature->name }}" />
                            @else
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="status">داغ/سرد</label>
                            <select class="form-control" id="status" name="status">
                                @if ($temperature->status=='hot')
                                <option value="hot" selected>داغ</option>
                                @else
                                <option value="hot">داغ</option>
                                @endif
                                @if ($temperature->type=='cold')
                                <option value="cold" selected>سرد</option>
                                @else
                                <option value="cold">سرد</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="type">نوع</label>
                            <select class="form-control" id="type" name="type">
                                @if ($temperature->type=='global')
                                <option value="global" selected>عمومی</option>
                                @else
                                <option value="global">عمومی</option>
                                @endif
                                @if ($temperature->type=='only_manager')
                                <option value="only_manager" selected>فقط مدیر</option>
                                @else
                                <option value="only_manager">فقط مدیر</option>
                                @endif
                                @if ($temperature->type=='only_supporter')
                                <option value="only_supporter" selected>فقط پشتبان</option>
                                @else
                                <option value="only_supporter">فقط پشتیبان</option>
                                @endif
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
    $(document).ready(function(){
        $('select.select2').select2();
    });
</script>
@endsection
