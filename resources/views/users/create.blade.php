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
                            <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور" />
                        </div>

                        <div class="form-group">
                            <label for="image_path">تصویر</label>
                            @if (isset($user) && $user->image_path && file_exists(public_path() . '/uploads/' . $user->image_path))
                            <img src="{{ '/uploads/' . $user->image_path }}" style="height: 30px;" />
                            @endif
                            <input type="file" class="form-control" id="image_path" name="image_path" />
                        </div>

                        <div class="form-group">
                            <label for="national_code">کد ملی</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="national_code" name="national_code" placeholder="کد ملی" value="{{ $user->national_code }}" />
                            @else
                            <input type="text" class="form-control" id="national_code" name="national_code" placeholder="کد ملی" value="{{ old('national_code') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="major">رشته تحصیلی</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="major" name="major" placeholder="رشته تحصیلی" value="{{ $user->major }}" />
                            @else
                            <input type="text" class="form-control" id="major" name="major" placeholder="رشته تحصیلی" value="{{ old('major') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="mobile">موبایل</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="موبایل" value="{{ $user->mobile }}" />
                            @else
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="موبایل" value="{{ old('mobile') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="home_address">آدرس منزل</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="home_address" name="home_address" placeholder="منزل" value="{{ $user->home_address }}" />
                            @else
                            <input type="text" class="form-control" id="home_address" name="home_address" placeholder="منزل" value="{{ old('home_address') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="max_student">حداکثر دانش آموز</label>
                            @if (isset($user))
                            <input type="number" class="form-control" id="max_student" name="max_student" placeholder="دانش آموز" value="{{ $user->max_student }}" />
                            @else
                            <input type="number" class="form-control" id="max_student" name="max_student" placeholder="دانش آموز" value="{{ old('max_student') }}" />
                            @endif
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
                            <label for="email">نام کاربری (ایمیل)</label>
                            @if (isset($user))
                            <input autocomplete="off" type="text" class="form-control" id="email" name="email" placeholder="نام کاربری" value="{{ $user->email }}" />
                            @else
                            <input autocomplete="off" type="text" class="form-control" id="email" name="email" placeholder="نام کاربری" value="{{ old('email') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">تکرار رمز عبور</label>
                            <input type="password" class="form-control" id="repassword" name="repassword" placeholder="تکرار رمز عبور"  />
                        </div>

                        <div class="form-group">
                            <label for="gender">جنسیت</label>
                            <select class="form-control" id="gender" name="gender" >
                                @if (isset($user) && $user->gender == "male")
                                <option value="male" selected>مرد</option>
                                @else
                                <option value="male" >مرد</option>
                                @endif
                                @if (isset($user) && $user->gender == "female")
                                <option value="female" selected>زن</option>
                                @else
                                <option value="female" >زن</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="education">تحصیلات</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="education" name="education" placeholder="تحصیلات" value="{{ $user->education }}" />
                            @else
                            <input type="text" class="form-control" id="education" name="education" placeholder="تحصیلات" value="{{ old('education') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="home_phone">تلفن منزل</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="home_phone" name="home_phone" placeholder="تلفن منزل" value="{{ $user->home_phone }}" />
                            @else
                            <input type="text" class="form-control" id="home_phone" name="home_phone" placeholder="تلفن منزل" value="{{ old('home_phone') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="work_mobile">شماره برای دانش آموزان</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="work_mobile" name="work_mobile" placeholder="موبایل" value="{{ $user->work_mobile }}" />
                            @else
                            <input type="text" class="form-control" id="work_mobile" name="work_mobile" placeholder="موبایل" value="{{ old('work_mobile') }}" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="work_address">آدرس کار</label>
                            @if (isset($user))
                            <input type="text" class="form-control" id="work_address" name="work_address" placeholder="کار" value="{{ $user->work_address }}" />
                            @else
                            <input type="text" class="form-control" id="work_address" name="work_address" placeholder="کار" value="{{ old('work_address') }}" />
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
