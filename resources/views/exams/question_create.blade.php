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
              <h1>سوال آزمون {{ $exam->name }}</h1>
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
                <input type="hidden" id="selected_image" name="selected_image" value="" />
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="lessons_id">درس</label>
                            <select class="form-control select2" id="lessons_id" name="lessons_id" >
                                <option value="0"> - </option>
                                @foreach ($lessons as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="order">ترتیب</label>
                            <input type="number" class="form-control" id="order" name="order" placeholder="ترتیب"  />
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="factor">ضریب</label>
                            <input type="number" class="form-control" id="factor" name="factor" placeholder="ضریب"  />
                        </div>

                        <div class="form-group">
                            <label for="description">توضیحات</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات"  />
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
                <div id="image_thumbnails">
                    <h1>
                        صفحه مورد نظر را انتخاب کنید:
                    </h1>
                    @foreach($images as $indx=>$image)
                    <img src="{{ $image }}" id="image-{{ $indx }}" style="width: 248px;height: 351px;cursor: pointer;" onclick="selectImage('image-{{ $indx }}');" />
                    @endforeach
                </div>
                <div id="image_selected">
                    <h3>
                        کادر انتخاب شده
                    </h3>
                    <canvas id="can"></canvas>
                    <h3>
                        صفحه انتخاب شده
                    </h3>
                    <canvas id="canvas"><!-- style="width: 1240px;height: 1754px;">-->
                    </canvas>
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
    var ctx;
    var canvas;
    var can;
    var ctx2;
    function selectImage(imgId) {
        var img1 = new Image();

        img1.onload = function () {
            canvas.width = img1.width;
            canvas.height = img1.height;
            ctx.drawImage(img1, 0,0,img1.width,img1.height,0,0,canvas.width,canvas.height);
        };

        img1.src = $("#" + imgId).prop('src');
    }

    function copy() {
        var imgData = ctx.getImageData(points[0].x, points[0].y, points[1].x - points[0].x, points[1].y - points[0].y);
        console.log(imgData);
        can.width = points[1].x - points[0].x;
        can.height = points[1].y - points[0].y;
        ctx2.putImageData(imgData, 0, 0);
        $("#selected_area").prop('src', can.toDataURL());

        var dataURL = can.toDataURL();
        var blobBin = atob(dataURL.split(',')[1]);

        var array = [];
        for(var i = 0; i < blobBin.length; i++) {
            array.push(blobBin.charCodeAt(i));
        }
        var file=new Blob([new Uint8Array(array)], {type: 'image/png'});

        $("#selected_image").val('');
        var formdata = new FormData();
        formdata.append("selected_area", file);
        $.ajax({
            url: "",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
        }).done(function(respond){
            // alert(respond);
            if(respond.status){
                $("#selected_image").val(respond.image_path);
            }
        });
    }
    function draw(prevX, prevY, currX, currY) {
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
        ctx.lineTo(currX, currY);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.closePath();
    }
    var points = [];
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('select.select2').select2();
        canvas = document.getElementById('canvas');
        can = document.getElementById('can');
        $("#canvas").click(function(event) {
            console.log('click', event);
            points.push({
                x: event.offsetX,// - canvas.offsetLeft,
                y: event.offsetY// - canvas.offsetTop
            });
            if(points.length==2) {
                copy();
                // draw(points[0].x, points[0].y, points[1].x, points[1].y);
                console.log(points);
                points = [];
            }
        });
        if (canvas.getContext) {
            ctx = canvas.getContext('2d');
        }
        if (can.getContext) {
            ctx2 = can.getContext('2d');
        }
    });
</script>
@endsection
