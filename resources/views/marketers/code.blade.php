@extends('layouts.index')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1> لینک معرفی و کد شناسایی</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row pt-3">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-cyan">
                  <div class="inner">
                  <h3>{{ $code }}</h3>
    
                    <p>کد شناسایی من</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-id-badge"></i>
                  </div>
                </div>
            </div>

            <div class="col-md-9 col-sm-6 col-xs-12">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-gradient-olive elevation-1"><i class="fas fa-link"></i></span>
    
                  <div class="info-box-content">
                    <span class="info-box-text">لینک معرفی به دوستان</span>
                    
                    <span id="ref_link" class="info-box-number my-2" style="direction: ltr !important;" >
                        https://aref-group.ir/ثبت-نام/?referrer={{ base64_encode($code) }}
                    </span>
                    
                    <span id="cplink" style="cursor:pointer" onclick="takeCopy('#ref_link')" >
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
@section('js')
  <script>
      takeCopy = (element) => {
        var temp = $("<input>");
        $("body").append(temp);
        let txt = $.trim($(element).text());
        temp.val(txt).select();
        document.execCommand("copy");
        temp.remove();
        $("#cplink").append('<sub id="cplinkdone" class="border border-right text-danger p-1 m-1" >پیوند کپی شد</sub>');
        setTimeout(()=>{
          $("#cplinkdone").hide('fast',()=>{
            $("#cplinkdone").remove();
          });

        },5000);
      }
  </script>
@endsection
