@extends('layout')

@section('content')
<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          {{ $manifest->NAMACONSOLIDATOR.' ('.$invoice->template_type.')' }}
          <small class="pull-right">Date: {{ date('d F, Y') }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12 text-center margin-bottom">
            <h2><b>INVOICE</b></h2>
        </div>
      <div class="col-sm-6 invoice-col">
          <table>
              <tr>
                  <td><b>Kepada Yth,</b></td>
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td><b>{{ $manifest->CONSIGNEE }}</b></td>
              </tr>
              <tr>
                  <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                  <td><b>Ex. Kapal</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->VESSEL }}</td>
              </tr>
              <tr>
                  <td><b>No. B/L / D/O</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOHBL }}</td>
              </tr>
              <tr>
                  <td><b>Gross Weight</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ number_format($manifest->WEIGHT, 4) }} KGS</td>
              </tr>
              <tr>
                  <td><b>Measurment</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ number_format($manifest->MEAS, 4) }} CBM</td>
              </tr>
              <tr>
                  <td><b>Container</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOCONTAINER }} / {{ $manifest->SIZE }}</td>
              </tr>
          </table>
      </div>
      <!-- /.col -->
      <div class="col-sm-6 invoice-col">
          <table>
              <tr>
                  <td><b>No. Invoice</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ 'SAJ/LTT-CFS'.date('Y').'/'.$invoice->number }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. Masuk</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y', strtotime($manifest->tglmasuk)) }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. ETA</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y', strtotime($manifest->ETA)) }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. Keluar</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y ', strtotime($manifest->tglrelease)) }}</td>
              </tr>
              @if($invoice->renew == 'Y')
              <tr>
                  <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                  <td colspan="3"><b>(Perpanjang)</b></td>
              </tr>
              <tr>
                  <td><b>Tgl. Perpanjang</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y ', strtotime($invoice->renew_date)) }}</td>
              </tr>
              @endif
          </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <br /><br />
    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
          <h4>Lama Penumpukan : {{$invoice->days.' Hari'}}</h4>
        <table class="table table-striped" border="0">
          <thead>
          <tr>
            <th>&nbsp;</th>
            <th>Jasa</th>
            <th>M3/Ton</th>
            <th>Jumlah</th>     
            <th class="text-center" colspan="2">Harga</th>
            <th class="text-center">&nbsp;</th>
            <th class="text-center" colspan="2">Sub Total</th>
          </tr>
          </thead>
          <tbody>
            @foreach($items as $item)
            <tr>
                <td>@if($item->item_type == 'custom') <button onclick="if(!confirm('Apakah anda yakin ingin menghapus data ini?')){return false;}else{deleteItem({{$item->id}})}" class="btn btn-xs btn-danger delete-item-btn"><i class="fa fa-close"></i></button> @endif</td>
                <td>{{$item->item_name}}</td>
                <td>{{$item->item_cbm}}</td>
                <td>{{$item->item_qty}}</td>
                <td align="right">Rp.</td>
                <td align="right">{{ number_format($item->item_amount) }}</td>
                <td align="right">{{ ($item->item_tax > 0) ? 'PPn '.$item->item_tax.'%' : '' }}</td>
                <td align="right">Rp.</td>
                <td align="right">{{ number_format($item->item_subtotal) }}</td>
            </tr>
            @endforeach          
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
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
              <td align="right">{{ number_format($invoice->subtotal_amount) }}</td>
            </tr>
            <tr>
              <th align="right">PPn</th>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->total_tax) }}</td>
            </tr>
            <tr>
              <th align="right">Total</th>
              <td align="right"><b>Rp.</b></td>
              <td align="right"><b>{{ number_format($invoice->total_amount) }}</b></td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
          <button id="print-invoice-btn" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp; Print</button>
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
            <form id="create-invoice-form" class="form-horizontal" action="{{ route('invoice-custom-item-add') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="invoice_id" type="hidden" id="invoice_id" value="{{$invoice->id}}" />
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
                                        <input type="number" id="price" name="price" class="form-control pull-right" required> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mainprice">
                                <label class="col-sm-3 control-label">Qty</label>
                                <div class="col-sm-4">
                                    <input type="number" id="qty" name="qty" class="form-control pull-right" required value="1"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tax(%)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" id="tax" name="tax" class="form-control pull-right" required value="0">
                                        <div class="input-group-addon">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@endsection

@section('custom_js')

<script type="text/javascript">
    function deleteItem(item_id)
    {
        alert(item_id);
    }
    $(document).ready(function()
    {
        $('#print-invoice-btn').click(function() {
            window.open("{{ route('invoice-print',$invoice->id) }}","preview invoice ","width=600,height=600,menubar=no,status=no,scrollbars=yes");
        });
        $('#add-billing-btn').on("click", function(){
            $('#add-billing-modal').modal('show');
        });
    });
    
</script>

@endsection