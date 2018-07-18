@extends('layout')

@section('content')

@include('partials.form-alert')

<div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Form Billing Template</h3>
    </div>
    <!-- /.box-header -->
    <form class="form-horizontal" action="{{ route('billing-template-store') }}" method="POST">
        <div class="box-body">            
            <div class="row">
                <div class="col-md-6">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group">
                      <label for="type" class="col-sm-3 control-label">Type</label>
                      <div class="col-sm-8">
                            <select class="form-control select2 select2-hidden-accessible" name="type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                <option value="Full" selected>Full Billing</option>
                                <option value="Half">50:50</option>
                            </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Template Name</label>
                      <div class="col-sm-8">
                          <input type="text" name="name" class="form-control" id="name" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roles" class="col-sm-3 control-label">Consolidator</label>
                      <div class="col-sm-8">
                            <select class="form-control select2 select2-hidden-accessible" name="consolidator_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                <option value="">Choose Consolidator</option>
                                @foreach($consolidators as $consolidator)
                                    <option value="{{ $consolidator->id }}">{{ $consolidator->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
<!--                    <div class="form-group">
                      <label for="recap_tax" class="col-sm-3 control-label">Recap Taxs</label>
                      <div class="col-sm-8">
                          <input type="number" name="recap_tax" class="form-control" id="recap_tax">
                      </div>
                    </div>-->
                    <div class="form-group">
                      <label for="min_meas" class="col-sm-3 control-label">Min. Meas</label>
                      <div class="col-sm-5">
                          <input type="number" name="min_meas" class="form-control" id="min_meas" required value="2">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="rounding" class="col-sm-3 control-label">Rounding</label>
                      <div class="col-sm-8">
                          <input type="checkbox" name="rounding" id="rounding" value="1" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="rounding_value" class="col-sm-3 control-label">Rounding Value</label>
                      <div class="col-sm-3">
                            <select class="form-control select2 select2-hidden-accessible" name="rounding_value" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="Y" selected>1</option>
                                <option value="H">0.5</option>
                            </select>
                      </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group">
                      <label for="day_by" class="col-sm-3 control-label">Day By</label>
                      <div class="col-sm-8">
                            <select class="form-control select2 select2-hidden-accessible" name="day_by" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                <option value="OB" selected>Tanggal Masuk / OB</option>
                                <option value="ETA">Tanggal ETA</option>
                            </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="warehouse" class="col-sm-3 control-label">Warehouse</label>
                      <div class="col-sm-8">
                          <input type="checkbox" name="warehouse" id="warehouse" value="1" checked />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="forwarder" class="col-sm-3 control-label">Forwarder</label>
                      <div class="col-sm-8">
                          <input type="checkbox" name="forwarder" id="forwarder" value="1" checked />
                      </div>
                    </div> 
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Simpan</button>
            <a href="{{ route('billing-template') }}" class="btn btn-danger pull-right" style="margin-right: 10px;"><i class="fa fa-close"></i> Keluar</a>
        </div>
        <!-- /.box-footer -->
    </form>
</div>

@endsection

@section('custom_css')

<!-- Select2 -->
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />

<!-- Bootstrap Switch -->
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">
@endsection

@section('custom_js')

<!-- Select2 -->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<!-- Bootstrap Switch -->
<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>

<script type="text/javascript">
    $('select').select2(); 
  
//    $.fn.bootstrapSwitch.defaults.size = 'mini';
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Ya';
    $.fn.bootstrapSwitch.defaults.offText = 'Tidak';
    
    $("input[type='checkbox']").bootstrapSwitch();
  
</script>

@endsection
