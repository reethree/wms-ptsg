@extends('print-with-noheader')

@section('title')
    {{ 'Rekap Mechanic '.$rekap->number }}
@stop

@section('content')
<style>
    @media print {
        body {
            color: #000;
            background: #fff;
        }
        @page {
            size: auto;   /* auto is the initial value */
            margin-top: 114px;
            margin-bottom: 90px;
            margin-left: 38px;
            margin-right: 75px;
            font-weight: bold;
        }
        .print-btn {
            display: none;
        }
    }
</style>
<?php 
    $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
    $bulan = $array_bulan[date('n')];
    $i = 1;$j = 1;
?>
<a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a>
<div id="details" class="clearfix">
    <div class="row invoice-info">
        <div class="col-sm-6 invoice-col">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3">Kepada Yth, </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <b>{{ $consolidator->NAMACONSOLIDATOR }}</b><br />
                        {{ $consolidator->ALAMAT }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="text-align: center;">
            <h2 style="margin-bottom: 0;">REKAP MECHANIC</h2>
            <p style="margin-top: 0;font-size: 14px;">{{$rekap->number.'/LTT/GDYS/'.$bulan.'/'.date('Y')}}</p>
        </div>
        <div class="col-sm-6 invoice-col">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><b>VESSEL:</b> {{ $container->VESSEL }}</td>
                    <td><b>TGL. ETA:</b> {{ date('d/m/Y', strtotime($container->ETA)) }}</td>
                    <td><b>CONTAINER:</b> {{ $container->NOCONTAINER.'/'.$container->SIZE }}</td>
                    <td><b>MB/L:</b> {{ $container->NOMBL }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="row">
      <div class="col-xs-12 table-responsive">
          <table border="1" cellspacing="0" cellpadding="0">
              <thead>
              <tr>
                <th>NO</th>
                <th>HB/L</th>
                <th>CONSIGNEE</th>
                <th>KGS</th>     
                <th>CBM</th>
                <th>NILAI INVOICE</th>
              </tr>
              </thead>
              <tbody>
                @foreach($itemsMain as $item)
                <tr>
                    <td style="text-align: center;border-top: none;border-bottom: none;">{{$i}}</td>
                    <td style="border-top: none;border-bottom: none;">{{$item->hbl}}</td>
                    <td style="border-top: none;border-bottom: none;">{{$item->consignee}}</td>
                    <td style="text-align: center;border-top: none;border-bottom: none;">{{$item->kgs}}</td>
                    <td style="text-align: center;border-top: none;border-bottom: none;">{{$item->cbm}}</td>
                    <td style="text-align: right;border-top: none;border-bottom: none;">{{number_format($item->amount)}}</td>
                </tr>
                <?php $i++;?>
                @endforeach          
              </tbody>
          </table>
      </div>
    </div>
    
    <div class="row">
        <h4>Additional Costs</h4>
      <div class="col-xs-12 table-responsive">
          <table border="1" cellspacing="0" cellpadding="0">
              <tbody>
                @foreach($itemsAdd as $itemAdd)
                <tr>
                    <td style="text-align: center;border-top: none;border-bottom: none;">{{$j}}</td>
                    <td style="border-top: none;border-bottom: none;">{{$itemAdd->name}}</td>
                    <td style="text-align: right;border-top: none;border-bottom: none;">{{number_format($itemAdd->amount)}}</td>
                </tr>
                <?php $j++;?>
                @endforeach        
              </tbody>
          </table>
      </div>
    </div>
    
    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6" style="width: 70%;float: left;">
          <p style="font-size: 12px;"><b>TERBILANG :</b> {{ $terbilang }}</p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6" style="width: 30%;float: right;">
        <!--<p class="lead">Amount Due 2/22/2014</p>-->

        <div class="table-responsive">
          <table>
            <tbody>
                <tr>
                  <td style="width:100%" align="right">Total Tanpa Pajak</td>
                  <td align="right" style="width:10px;text-align: right;">Rp.</td>
                  <td align="right" style="text-align: right;">{{ number_format($rekap->subtotal) }}</td>
                </tr>
                <tr>
                  <td align="right">PPn {{$rekap->tax.'%'}}</td>
                  <td align="right" style="text-align: right;">Rp.</td>
                  <td align="right" style="text-align: right;">{{ number_format($rekap->ppn) }}</td>
                </tr>
                <tr>
                  <td align="right"><b>Total</b></td>
                  <td align="right" style="text-align: right;"><b>Rp.</b></td>
                  <td align="right" style="text-align: right;"><b>{{ number_format($rekap->total) }}</b></td>
                </tr>
              </tbody>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <div class="table-responsive">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Jakarta, {{ ($rekap->printed_at == NULL) ? date('d F Y') : date('d F Y', strtotime($rekap->printed_at)) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><b>{{$rekap->officer}}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <p style="font-size: 10px;">
            Pembayaran harus ditransfer ke Lautan Tirta Transportama <b>IDR : 007.30.15283</b> a/n Lautan Tirta Transportama. BCA Jl. Yos Sudarso 14 dan bukti pembayaran di-fax ke: 4394558 <b>ATTN: {{$rekap->officer}}</b>
        </p>
    </div>
</div>
@stop