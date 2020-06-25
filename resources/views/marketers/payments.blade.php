@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1> وصولی های من</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row pt-3">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">
                  <h3>10</h3>
    
                    <p>وصولیهای من</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-id-badge"></i>
                  </div>
                </div>
            </div>

            <div class="col-md-9 col-sm-6 col-xs-12">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-link"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">لینک معرفی به دوستان</span>
                    
                    <span class="info-box-number my-2" style="direction: ltr !important;" >
                        https://aref-group.ir/ثبت-نام/
                    </span>
                    
                    <span style="cursor:pointer" >
                        <i class="far fa-copy"></i>
                        کپی پیوند
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
@endsection
