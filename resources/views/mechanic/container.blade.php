@extends('layout')

@section('content')
<?php 
    $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
    $bulan = $array_bulan[date('n')];
?>
<script>
    
    var grid = $("#lclMechanicGrid"), headerRow, rowHight, resizeSpanHeight;

    // get the header row which contains
    headerRow = grid.closest("div.ui-jqgrid-view")
        .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");

    // increase the height of the resizing span
    resizeSpanHeight = 'height: ' + headerRow.height() +
        'px !important; cursor: col-resize;';
    headerRow.find("span.ui-jqgrid-resize").each(function () {
        this.style.cssText = resizeSpanHeight;
    });

    // set position of the dive with the column header text to the middle
    rowHight = headerRow.height();
    headerRow.find("div.ui-jqgrid-sortable").each(function () {
        var ts = $(this);
        ts.css('top', (rowHight - ts.outerHeight()) / 2 + 'px');
    });
    
    function gridCompleteEvent()
    {
        var ids = jQuery("#lclMechanicGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            edt = '<a href="{{ route("mechanic-rekap-detail",'') }}/'+cl+'"><i class="fa fa-print"></i></a> ';
//            jQuery("#lclMechanicGrid").jqGrid('setRowData',ids[i],{action:edt}); 
        } 
    }
    
    function onSelectRowEvent()
    {
        rowid = $('#lclMechanicGrid').jqGrid('getGridParam', 'selrow');
        rowdata = $('#lclMechanicGrid').getRowData(rowid);

        $("#container_id").val(rowdata.TCONTAINER_PK);
        $("#consolidator_id").val(rowdata.TCONSOLIDATOR_FK);
    }
    
    $(document).ready(function()
    {      
        $('#btn-rekap').on("click", function(){
            rowid = $('#lclMechanicGrid').jqGrid('getGridParam', 'selrow');
            if(rowid){
                $('#create-rekap-modal').modal('show');
            }else{
                alert('Please select data first.');
            }
        });
        
        $('#create-rekap-form').on("submit", function(){
            if(!confirm('Apakah anda yakin?')){return false;}
            
            //Gets the selected row id.
//            rowid = $('#lclReleaseGrid').jqGrid('getGridParam', 'selrow');
//            rowdata = $('#lclReleaseGrid').getRowData(rowid);
            
//            if(rowdata.INVOICE == ''){
//                alert('Please Select Type of Invoice');
//                return false;
//            }
        });
    });
    
</script>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Lists Container</h3>
        <div class="box-tools">
            <button class="btn btn-info btn-sm" id="btn-rekap"><i class="fa fa-print"></i> Create Rekap</button>
        </div>
    </div>
    <div class="box-body table-responsive">
        {{
            GridRender::setGridId("lclMechanicGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/lcl/register/grid-data?ref=mechanic&_token='.csrf_token()))
            ->setGridOption('rowNum', 20)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','TCONTAINER_PK')
            ->setGridOption('rownumbers', true)
            ->setGridOption('height', '390')
            ->setGridOption('rowList',array(20,50,100))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
//            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('key'=>true,'index'=>'TCONTAINER_PK','hidden'=>true))
            ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER','width'=>160))
            ->addColumn(array('label'=>'No. Joborder','index'=>'NoJob','width'=>160))
            ->addColumn(array('label'=>'No. MBL','index'=>'NOMBL','width'=>160))
            ->addColumn(array('label'=>'Tgl. MBL','index'=>'TGL_MASTER_BL','width'=>150,'align'=>'center'))
            ->addColumn(array('index'=>'TCONSOLIDATOR_FK','hidden'=>true))
            ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250))
            ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Teus','index'=>'TEUS', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'No. Seal','index'=>'NO_SEAL', 'width'=>120,'align'=>'right'))
//            ->addColumn(array('label'=>'Layout','index'=>'layout','width'=>80,'align'=>'center','hidden'=>true))
//            ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150))
            ->addColumn(array('label'=>'Tgl. Entry','index'=>'TGLENTRY', 'width'=>150,'align'=>'center'))
//            ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false))
//            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>

<div id="create-rekap-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Rekap Mechanic Form</h4>
            </div>
            <form id="create-invoice-form" class="form-horizontal" action="{{ route("mechanic-rekap-create") }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            <input name="container_id" type="hidden" id="container_id" />
                            <input name="consolidator_id" type="hidden" id="consolidator_id" />
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. Rekap</label>
                                <div class="col-sm-6">
                                    <div class="input-group date">                                  
                                        <input type="number" class="form-control pull-left" name="number" required />
                                        <div class="input-group-addon">
                                            {{'/LTT/GDYS/'.$bulan.'/'.date('Y')}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tax</label>
                                <div class="col-sm-5">
                                    <input type="number" name="tax" value="10" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pembulatan</label>
                                <div class="col-sm-5">
                                    <input type="checkbox" name="rounding" value="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tgl. Cetak</label>
                                <div class="col-sm-6">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="printed_at" class="form-control pull-right datepicker" required value="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Petugas</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="officer" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        <option value="DJAENUDIN">DJAENUDIN</option>
                                        <option value="..">..</option>
                                        <option value="...">...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
                  <button type="submit" class="btn btn-primary">Create Rekap</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('custom_css')

<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<!-- Bootstrap Switch -->
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">
<!--<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css") }}">-->

@endsection

@section('custom_js')

<script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script type="text/javascript">
    $(".select2").select2();
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        zIndex: 99
    });
    
//    $.fn.bootstrapSwitch.defaults.size = 'mini';
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Yes';
    $.fn.bootstrapSwitch.defaults.offText = 'No';
    $("input[type='checkbox']").bootstrapSwitch();
</script>

@endsection