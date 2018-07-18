@extends('layout')

@section('content')

@include('partials.form-alert')
<?php 
    $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
    $bulan = $array_bulan[date('n')];
    $i = 1;$j = 1;
?>
<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          {{ $container->NAMACONSOLIDATOR }}
          <small class="pull-right">Date: {{ date('d F, Y') }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12 text-center margin-bottom">
            <h2><b>REKAP MECHANIC</b></h2>
            <h4>{{$rekap->number.'/LTT/GDYS/'.$bulan.'/'.date('Y')}}</h4>
        </div>
        <div class="col-sm-12 invoice-col">
            <div class="col-sm-3"><p><b>VESSEL : </b>{{ $container->VESSEL }}</p></div>
            <div class="col-sm-3"><p><b>TGL. ETA : </b>{{ date('d/m/Y', strtotime($container->ETA)) }}</p></div>
            <div class="col-sm-3"><p><b>CONTAINER : </b>{{ $container->NOCONTAINER }} / {{ $container->SIZE }}</p></div>
            <div class="col-sm-3"><p><b>MB/L : </b>{{ $container->NOMBL }}</p></div>
        </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <br /><br />
    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <form method="POST" action="{{route('mechanic-rekap-update')}}">
            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
            <input name="rekap_id" type="hidden" id="rekap_id" value="{{$rekap->id}}" />
            <table class="table table-striped" border="0">
              <thead>
              <tr>
                <th>NO</th>
                <th>HB/L</th>
                <th>CONSIGNEE</th>
                <th>KGS</th>     
                <th>CBM</th>
                <th>NILAI INVOICE</th>
                <th>TARIF</th>
              </tr>
              </thead>
              <tbody>
                @foreach($itemsMain as $item)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$item->hbl}}</td>
                    <td>{{$item->consignee}}</td>
                    <td>{{$item->kgs}}</td>
                    <td>{{$item->cbm}}</td>
                    <td align="center">{{number_format($item->amount)}}</td>
                    <td>
                        <select class="form-control select2" name="{{$item->hbl.'|'.$item->id}}" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="{{$tarif->tarif1}}" @if($item->tarif == $tarif->tarif1) {{'selected'}} @endif>{{ number_format($tarif->tarif1) }}</option>
                            @if($tarif->tarif2 > 0)
                            <option value="{{$tarif->tarif2}}" @if($item->tarif == $tarif->tarif2) {{'selected'}} @endif>{{ number_format($tarif->tarif2) }}</option>
                            @endif
                            @if($tarif->tarif3 > 0)
                            <option value="{{$tarif->tarif3}}" @if($item->tarif == $tarif->tarif3) {{'selected'}} @endif>{{ number_format($tarif->tarif3) }}</option>
                            @endif
                        </select>
                    </td>
                </tr>
                <?php $i++;?>
                @endforeach          
              </tbody>
            </table>
            <button type="submit" class="btn btn-warning pull-right">Update Data Tarif</button>
        </form>
      </div>
      <!-- /.col -->
    </div>
    
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <h4>Additional Costs</h4>
        <table class="table table-striped" border="0">
          <tbody>
            @foreach($itemsAdd as $itemAdd)
            <tr>
                <td>{{$j}}</td>
                <td>{{$itemAdd->name}}</td>
                <td align="center">{{number_format($itemAdd->amount)}}</td>
                <td align="right"><button onclick="if(!confirm('Apakah anda yakin ingin menghapus data ini?')){return false;}else{deleteItem({{$itemAdd->id}})}" class="btn btn-xs btn-danger delete-item-btn"><i class="fa fa-close"></i></button></td>
            </tr>
            <?php $j++;?>
            @endforeach          
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    
    <div class="row" style="margin-top: 50px;">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p>Terbilang:</p>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; color: #000;">
          {{ $terbilang }}
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <!--<p class="lead">Amount Due 2/22/2014</p>-->

        <div class="table-responsive">
          <table class="table">
            <tbody>
            <tr>
              <th style="width:50%" align="right">Total Tanpa Pajak</th>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($rekap->subtotal) }}</td>
            </tr>
            <tr>
              <th align="right">PPn {{$rekap->tax.'%'}}</th>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($rekap->ppn) }}</td>
            </tr>
            <tr>
              <th align="right">Total</th>
              <td align="right"><b>Rp.</b></td>
              <td align="right"><b>{{ number_format($rekap->total) }}</b></td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    
    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
          <button id="print-rekap-btn" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp; Print</button>
          <button id="add-billing-btn" type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i>&nbsp; Add Custom Billing</button>
      </div>
    </div>
  </section>

<div id="add-billing-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Add Custom Billing</h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{ route('mechanic-custom-item-add') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="rekap_id" type="hidden" id="rekap_id" value="{{$rekap->id}}" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Item Name</label>
                                <div class="col-sm-6">
                                    <input type="text" id="item_name" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group mainprice">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            IDR                                    
                                        </div>
                                        <input type="number" id="price" name="amount" class="form-control pull-right" required> 
                                    </div>
                                </div>
                            </div>
<!--                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tax(%)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" id="tax" name="tax" class="form-control pull-right" required value="0">
                                        <div class="input-group-addon">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection

@section('custom_css')

<!-- Select2 -->
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />

<!-- Bootstrap Switch -->
<!--<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">-->
@endsection

@section('custom_js')

<!-- Select2 -->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<!-- Bootstrap Switch -->
<!--<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>-->

<script type="text/javascript">
     
     $('select').select2();
//    $.fn.bootstrapSwitch.defaults.size = 'mini';
//    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
//    $.fn.bootstrapSwitch.defaults.onText = 'Ya';
//    $.fn.bootstrapSwitch.defaults.offText = 'Tidak';
//    
//    $("input[type='checkbox']").bootstrapSwitch();
  
    $(document).ready(function(){
        
        
        $('#print-rekap-btn').click(function() {
            window.open("{{ route('mechanic-print',$rekap->id) }}","preview invoice ","width=600,height=600,menubar=no,status=no,scrollbars=yes");
        });
        
        $('#add-billing-btn').on("click", function(){
            $('#add-billing-modal').modal('show');
        });
    });
  
</script>

@endsection
