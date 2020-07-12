@extends('layouts.index')

@section('css')
<link href="/plugins/select2/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1>افزودن دانش آموز جدید</h1>
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
                            <label for="first_name">نام</label>
                            @if (isset($student) && isset($student->id))
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام" value="{{ $student->first_name }}" />
                            @else
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="نام"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="last_year_grade">تراز یا رتبه سال قبل</label>
                            @if (isset($student) && isset($student->id))
                            <input type="number" class="form-control" id="last_year_grade" name="last_year_grade" placeholder="تراز/رتبه" value="{{ $student->last_year_grade }}" />
                            @else
                            <input type="number" class="form-control" id="last_year_grade" name="last_year_grade" placeholder="تراز/رتبه"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="parents_job_title">شغل پدر یا مادر</label>
                            @if (isset($student) && isset($student->id))
                            <input type="text" class="form-control" id="parents_job_title" name="parents_job_title" placeholder="شغل" value="{{ $student->parents_job_title }}" />
                            @else
                            <input type="text" class="form-control" id="parents_job_title" name="parents_job_title" placeholder="شغل"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="egucation_level">مقطع</label>
                            <select  id="egucation_level" name="egucation_level" class="form-control">
                                <option value=""></option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "6")
                                <option value="6" selected>
                                @else
                                <option value="6" >
                                @endif
                                6
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "7")
                                <option value="7" selected>
                                @else
                                <option value="7" >
                                @endif
                                7
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "8")
                                <option value="8" selected>
                                @else
                                <option value="8" >
                                @endif
                                8
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "9")
                                <option value="9" selected>
                                @else
                                <option value="9" >
                                @endif
                                9
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "10")
                                <option value="10" selected>
                                @else
                                <option value="10" >
                                @endif
                                10
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "11")
                                <option value="11" selected>
                                @else
                                <option value="11" >
                                @endif
                                11
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "12")
                                <option value="12" selected>
                                @else
                                <option value="12" >
                                @endif
                                12
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "13")
                                <option value="13" selected>
                                @else
                                <option value="13" >
                                @endif
                                فارغ التحصیل
                                </option>
                                @if (isset($student) && isset($student->id) && $student->egucation_level == "14")
                                <option value="14" selected>
                                @else
                                <option value="14" >
                                @endif
                                دانشجو
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mother_phone">تلفن مادر</label>
                            @if (isset($student) && isset($student->id))
                            <input type="number" class="form-control" id="mother_phone" name="mother_phone" placeholder="تلفن مادر" value="{{ $student->mother_phone }}" />
                            @else
                            <input type="number" class="form-control" id="mother_phone" name="mother_phone" placeholder="تلفن مادر"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="school">مدرسه</label>
                            @if (isset($student) && isset($student->id))
                            <input type="text" class="form-control" id="school" name="school" placeholder="مدرسه" value="{{ $student->school }}" />
                            @else
                            <input type="text" class="form-control" id="school" name="school" placeholder="مدرسه"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="major">رشته</label>
                            <select  id="major" name="major" class="form-control">
                                <option value=""></option>
                                @if (isset($student) && isset($student->id) && $student->major == "mathematics")
                                <option value="mathematics" selected>
                                @else
                                <option value="mathematics" >
                                @endif
                                ریاضی
                                </option>
                                @if (isset($student) && isset($student->id) && $student->major == "experimental")
                                <option value="experimental" selected>
                                @else
                                <option value="experimental" >
                                @endif
                                تجربی
                                </option>
                                @if (isset($student) && isset($student->id) && $student->major == "humanities")
                                <option value="humanities" selected>
                                @else
                                <option value="humanities" >
                                @endif
                                انسانی
                                </option>
                                @if (isset($student) && isset($student->id) && $student->major == "art")
                                <option value="art" selected>
                                @else
                                <option value="art" >
                                @endif
                                هنر
                                </option>
                                @if (isset($student) && isset($student->id) && $student->major == "other")
                                <option value="other" selected>
                                @else
                                <option value="other" >
                                @endif
                                دیگر
                                </option>
                            </select>
                        </div>

                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="last_name">نام خانوادگی <span style="color: red;">*</span></label>
                            @if (isset($student) && isset($student->id))
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی" value="{{ $student->last_name }}" required />
                            @else
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="نام خانوادگی" required />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="home_phone">تلفن منزل</label>
                            @if (isset($student) && isset($student->id))
                            <input type="number" class="form-control" id="home_phone" name="home_phone" placeholder="تلفن منزل" value="{{ $student->home_phone }}" />
                            @else
                            <input type="number" class="form-control" id="home_phone" name="home_phone" placeholder="تلفن منزل"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="father_phone">تلفن پدر</label>
                            @if (isset($student) && isset($student->id))
                            <input type="number" class="form-control" id="father_phone" name="father_phone" placeholder="تلفن پدر" value="{{ $student->father_phone }}" />
                            @else
                            <input type="number" class="form-control" id="father_phone" name="father_phone" placeholder="تلفن پدر"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">تلفن <span style="color: red;">*</span></label>
                            @if (isset($student) && isset($student->id))
                            <input required type="number" class="form-control" id="phone" name="phone" placeholder="تلفن" value="{{ $student->phone }}" />
                            @else
                            <input required type="number" class="form-control" id="phone" name="phone" placeholder="تلفن"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="average">معدل</label>
                            @if (isset($student) && isset($student->id))
                            <input type="text" class="form-control" id="average" name="average" placeholder="معدل" value="{{ $student->average }}" />
                            @else
                            <input type="text" class="form-control" id="average" name="average" placeholder="معدل"  />
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="student_phone">تلفن دانش آموز</label>
                            @if (isset($student) && isset($student->id))
                            <input type="number" class="form-control" id="student_phone" name="student_phone" placeholder="تلفن" value="{{ $student->student_phone }}" />
                            @else
                            <input type="number" class="form-control" id="student_phone" name="student_phone" placeholder="تلفن"  />
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
