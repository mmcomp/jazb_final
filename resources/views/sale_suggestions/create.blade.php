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
              <h1>شرط پیشنهاد</h1>
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
                            @if (isset($saleSuggestion) && isset($saleSuggestion->id))
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="نام" value="{{ $saleSuggestion->name }}" />
                            @else
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="نام" value="{{ old('name')}}" />

                            @endif
                            @error('name')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="if_moral_tags_id">برچسب اخلاقی</label>
                            <select  id="if_moral_tags_id" name="if_moral_tags_id[]" class="form-control select2 @error('if_moral_tags_id') is-invalid @enderror" multiple>
                                <option value=""></option>
                                @foreach ($moralTags as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->if_moral_tags_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ (collect(old('if_moral_tags_id'))->contains($item->id)) ? 'selected':'' }}>
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('if_moral_tags_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="if_schools_id">مدرسه</label>
                                {{-- @foreach ($schools as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->if_schools_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ old('if_schools_id') == $item->id ? 'selected' : ''}} >
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach --}}
                                @if (isset($saleSuggestion) && isset($saleSuggestion->id))
                                <input type="text" class="form-control @error('if_schools_id') is-invalid @enderror" id="if_schools_id" name="if_schools_id" placeholder="مدرسه" value="{{ $saleSuggestion->if_schools_id }}" />
                                @else
                                <input type="text" class="form-control @error('if_schools_id') is-invalid @enderror" id="if_schools_id" name="if_schools_id" placeholder="مدرسه" value="{{ old('if_schools_id')}}" />
                                @endif
                                @error('if_schools_id')
                                  <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group">
                            <label for="if_avarage">معدل</label>
                            @if(isset($saleSuggestion) && isset($saleSuggestion->id))
                            <input type="number" class="form-control @error('if_average') is-invalid @enderror" id="if_avarage" name="if_avarage" placeholder="معدل" value="{{ $saleSuggestion->if_avarage }}" />
                            @else
                            <input type="number" class="form-control @error('if_average') is-invalid @enderror" id="if_avarage" name="if_avarage" placeholder="معدل" value="{{ old('if_avarage')}}" />

                            @endif
                            @error('if_avarage')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="then_product1_id">محصول پیشنهادی 1</label>
                            <select  id="then_product1_id" name="then_product1_id" class="form-control select2 @error('then_product1_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($products as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->then_product1_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ old('then_product1_id') == $item->id ? 'selected' : ''}}>
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('then_product1_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="then_product3_id">محصول پیشنهادی 3</label>
                            <select  id="then_product3_id" name="then_product3_id" class="form-control select2 @error('then_product3_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($products as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->then_product3_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ old('then_product3_id') == $item->id ? 'selected' : ''}} >
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('then_product3_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="if_products_id">محصول</label>
                            <select  id="if_products_id" name="if_products_id[]" class="form-control select2 @error('if_products_id') is-invalid @enderror" multiple>
                                <option value=""></option>
                                @foreach ($products as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->if_products_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ (collect(old('if_products_id'))->contains($item->id)) ? 'selected':'' }}>
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('if_products_id')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="if_need_tags_id">برچسب نیازسنجی</label>
                            <select  id="if_need_tags_id" name="if_need_tags_id[]" class="select2 form-control @error('if_need_tags_id') is-invalid @enderror" multiple>
                                <option value=""></option>
                                @foreach ($needTags as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->if_need_tags_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}" {{ (collect(old('if_need_tags_id'))->contains($item->id)) ? 'selected':'' }}>
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('if_need_tags_id')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="if_last_year_grade">رتبه</label>
                            @if(isset($saleSuggestion) && isset($saleSuggestion->id))
                            <input type="number" class="form-control @error('if_last_year_grade') is-invalid @enderror" id="if_last_year_grade" name="if_last_year_grade" placeholder="رتبه" value="{{ $saleSuggestion->if_last_year_grade }}" />
                            @else
                            <input type="number" class="form-control @error('if_last_year_grade') is-invalid @enderror" id="if_last_year_grade" name="if_last_year_grade" placeholder="رتبه" value="{{ old('if_last_year_grade')}}" />

                            @endif
                            @error('if_last_year_grade')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="if_sources_id">منبع</label>
                            <select  id="if_sources_id" name="if_sources_id" class="form-control @error('if_sources_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($sources as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->if_sources_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}"{{ old('if_sources_id') == $item->id ? 'selected' : ''}} >
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('if_sources_id')
                              <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="then_product2_id">محصول پیشنهادی 2</label>
                            <select  id="then_product2_id" name="then_product2_id" class="form-control select2 @error('then_product2_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($products as $item)
                                    @if (isset($saleSuggestion) && isset($saleSuggestion->id) && $saleSuggestion->then_product2_id == $item->id)
                                    <option value="{{ $item->id }}" selected>
                                    @else
                                    <option value="{{ $item->id }}"{{ old('then_product2_id') == $item->id ? 'selected' : ''}} >
                                    @endif
                                    {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('then_product2_id')
                               <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
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
