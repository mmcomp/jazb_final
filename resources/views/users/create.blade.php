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
              <h1>کاربر</h1>
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
                <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <input autocomplete="off" name="hidden" type="text" style="display:none;">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="first_name">نام</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام" value="{{ $user->first_name }}" />
                            @else
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام" value="{{ old('first_name') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="groups_id">گروه</label>
                            <select class="form-control select2" id="groups_id" name="groups_id" >
                                @foreach ($groups as $item)
                                    @if (isset($user) && $user->groups_id == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">رمز عبور</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور"  />
                            <input type="password" class="form-control" id="repassword" name="repassword" placeholder="تکرار رمز عبور"  />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="last_name">نام خانوادگی</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی" value="{{ $user->last_name }}" required />
                            @else
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی" value="{{ old('last_name') }}" required />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email">نام کاربری</label>
                            @if (isset($user))
                            <input autocomplete="off" type="text" class="form-control" id="email" name="email" placeholder="نام کاربری" value="{{ $user->email }}" />
                            @else
                            <input autocomplete="off" type="text" class="form-control" id="email" name="email" placeholder="نام کاربری" value="{{ old('email') }}" />
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
