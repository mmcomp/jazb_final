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
              <h1>آزمون</h1>
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
                            @if (isset($exam) && isset($exam->id))
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ $exam->name }}" />
                            @else
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="question_pdf">فایل سوالات</label>
                            @if (isset($exam) && isset($exam->question_pdf) && $exam->question_pdf!=null && $exam->question_pdf!='')
                            <a target="_blank" href="{{ '/uploads/' . $exam->question_pdf }}">مشاهده</a>
                            @endif
                            <input type="file" class="form-control" id="question_pdf" name="question_pdf" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            @if (isset($exam) && isset($exam->id))
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ $exam->description }}" />
                            @else
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات"  />
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="answer_pdf">فایل پاسخ</label>
                            @if (isset($exam) && isset($exam->answer_pdf) && $exam->answer_pdf!=null && $exam->answer_pdf!='')
                            <a target="_blank" href="{{ '/uploads/' . $exam->answer_pdf }}">مشاهده</a>
                            @endif
                            <input type="file" class="form-control" id="answer_pdf" name="answer_pdf" />
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
