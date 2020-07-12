@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">داشبورد</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <!--
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
              -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-orange">
                <div class="inner p-0">
                  <!--<h3>150</h3>-->

                    <p class="text-center">
                        <a href="{{ route('students') }}" class="text-light btn">
                        تقسیم داشن آموز
                        <span class="badge badge-warning right">{{ $devideStudents }}</span>
                        </a>
                    </p>
                </div>
                <!--
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-orange">
                  <div class="inner p-0">
                    <!--<h3>150</h3>-->

                      <p class="text-center">
                        <a href="{{ route('students') }}" class="text-light btn">
                            ورودی امروز
                            <span class="badge badge-warning right">{{ $todayStudents }}</span>
                        </a>
                    </p>
                  </div>
                  <!--
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                  -->
                </div>
              </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-orange">
                  <div class="inner p-0">
                    <!--<h3>150</h3>-->

                      <p class="text-center">
                        <a href="{{ route('students') }}" class="text-light btn">
                            ورودی گذشته
                            <span class="badge badge-warning right">{{ $devideStudents - $todayStudents }}</span>
                        </a>
                    </p>
                  </div>
                  <!--
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                  -->
                </div>
              </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-orange">
                <div class="inner p-0">
                  <!--<h3>150</h3>-->

                    <p class="text-center">
                        <a href="{{ route('messages') }}" class="text-light btn">
                        پیام دریافتی از پشتیبان
                        </a>
                    </p>
                </div>
                <!--
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-orange">
                  <div class="inner p-0">
                    <!--<h3>150</h3>-->

                      <p class="text-center">
                        <a href="#" class="text-light btn">
                            گزارش فروش پشتیبان
                        </a>
                    </p>
                  </div>
                  <!--
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                  -->
                </div>
              </div>
            <!-- ./col -->
            <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner p-0">
                    <!--<h3>150</h3>-->

                      <p class="text-center">
                        <a href="{{ route('student_create') }}" class="text-light btn">
                            ثبت دانش آموز جدید
                        </a>
                    </p>
                  </div>
                  <!--
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                  -->
                </div>
              </div>
            <!-- ./col -->
          </div>
          <!-- /.row (main row) -->
          <div class="row">
            <div class="col-md-3">
                <h4 style="margin-top: 110px;">
                    تعداد کل ورودی های جدید
                </h4>
            </div>
            <div class="col-md-9">
                <div class="chart">
                    <div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="areaChart" style="height: 250px; min-height: 250px; display: block; width: 524px;" width="524" height="250" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
                <p>
                  <strong>تعداد دانش  آموزان پشتیبان</strong>
                </p>
                @foreach ($supporters as $item)
                <div class="progress-group">
                    {{ $item->first_name }} {{ $item->last_name }}
                    <span class="float-right"><b>{{ count($item->students) }}</b>/{{ $item->max_student?$item->max_student:0 }}</span>
                    <div class="progress progress-sm">
                       @if ($item->max_student && $item->max_student>0)
                        <div class="progress-bar bg-primary" style="width: {{ count($item->students)*100/$item->max_student }}%"></div>
                      @else
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                      @endif
                    </div>
                </div>
                <!-- /.progress-group -->
                @endforeach
              </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
@endsection

@section('js')
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<script>
    $(function () {
      /* ChartJS
       * -------
       * Here we will create a few charts using ChartJS
       */

      //--------------
      //- AREA CHART -
      //--------------

      // Get context with jQuery - using jQuery's .get() method.
      var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
      var mainLabels = [
             'فروردین'
            , 'اردیبهشت'
            , 'خرداد'
            , 'تیر'
            , 'مرداد'
            , 'شهریور'
            , 'مهر'
            , 'آبان'
            , 'آذر'
            , 'دی'
            , 'بهمن'
            , 'اسفند'
        ];
      var labels = [];
      var results = @JSON($results);
      for(var i = 0;i < results.length;i++){
          labels.push(mainLabels[i])
      }
      console.log(mainLabels, labels)
      var areaChartData = {
        labels ,
        datasets: [
          {
            label               : 'دانش آموزان',
            backgroundColor     : 'rgba(60,141,188,0.9)',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : results
          },
        //   {
        //     label               : 'Electronics',
        //     backgroundColor     : 'rgba(210, 214, 222, 1)',
        //     borderColor         : 'rgba(210, 214, 222, 1)',
        //     pointRadius         : false,
        //     pointColor          : 'rgba(210, 214, 222, 1)',
        //     pointStrokeColor    : '#c1c7d1',
        //     pointHighlightFill  : '#fff',
        //     pointHighlightStroke: 'rgba(220,220,220,1)',
        //     data                : [65, 59, 80, 81, 56, 55]
        //   },
        ]
      }

      var areaChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            }
          }]
        }
      }

      // This will get the first returned node in the jQuery collection.
      var areaChart       = new Chart(areaChartCanvas, {
        type: 'line',
        data: areaChartData,
        options: areaChartOptions
      })
    })
  </script>
@endsection
