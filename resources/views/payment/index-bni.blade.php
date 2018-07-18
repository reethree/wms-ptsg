@extends('layout')

@section('content')
<style>
    .datepicker.dropdown-menu {
        z-index: 9999 !important;
    }
    th.ui-th-column div{
        white-space:normal !important;
        height:auto !important;
        padding:2px;
    }
</style>
<script>
    
    var grid = $("#lclInvoicesGrid"), headerRow, rowHight, resizeSpanHeight;

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
        var ids = jQuery("#lclInvoicesGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            edt = '<a href="{{ route("invoice-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
            del = '<a href="{{ route("invoice-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
            jQuery("#lclInvoicesGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del}); 
        } 
    }
    
</script>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">BNI E-Collection Payment Lists</h3>
        <div class="box-tools">
            <button class="btn btn-block btn-info btn-sm" id="cetak-rekap"><i class="fa fa-plus"></i> Create Manual</button>
        </div>
    </div>
    <div class="box-body table-responsive">
        {{
            GridRender::setGridId("lclInvoicesGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/invoice/grid-data?_token='.csrf_token()))
            ->setFileProperty('title', 'LCL Invoices') //Laravel Excel File Property
            ->setFileProperty('creator', 'Reza') //Laravel Excel File Property
            ->setSheetProperty('fitToPage', true) //Laravel Excel Sheet Property
            ->setSheetProperty('fitToHeight', true)
            ->setGridOption('rowNum', 20)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','updated_at')
            ->setGridOption('rownumbers', true)
            ->setGridOption('height', '400')
            ->setGridOption('rowList',array(20,50,100))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
            ->addColumn(array('label'=>'No. Invoice','index'=>'no_invoice','width'=>200))
            ->addColumn(array('label'=>'Type','index'=>'INVOICE','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Consolidator','index'=>'NAMACONSOLIDATOR','width'=>250))
            ->addColumn(array('label'=>'Vessel','index'=>'VESSEL', 'width'=>150))
            ->addColumn(array('label'=>'Voy','index'=>'VOY','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'No. Container','index'=>'NOCONTAINER','width'=>160))
            ->addColumn(array('label'=>'Size','index'=>'SIZE', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Tanggal<br />Masuk','index'=>'tglmasuk', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'Tanggal<br />Keluar','index'=>'tglrelease', 'width'=>120, 'align'=>'center'))
            ->addColumn(array('label'=>'No. B/L','index'=>'NOHBL','width'=>160))          
            ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>250,))
            ->addColumn(array('label'=>'CBM<br r/>eq','index'=>'cbm', 'width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Hari','index'=>'hari','width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Bhndl','index'=>'behandle', 'width'=>60,'align'=>'center'))
            ->addColumn(array('label'=>'Storage','index'=>'storage','width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'RDM','index'=>'rdm','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Behandle','index'=>'harga_behandle', 'width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Adm/Doc','index'=>'adm','width'=>100,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Surcharge','index'=>'weight_surcharge', 'width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'Sub Total','index'=>'sub_total', 'width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
//            ->addColumn(array('label'=>'PPN','index'=>'ppn', 'width'=>120,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
            ->addColumn(array('label'=>'UID','index'=>'UID', 'width'=>150))
            ->addColumn(array('label'=>'Tanggal<br/>Entry','index'=>'created_at', 'width'=>160,'align'=>'center'))
//            ->addColumn(array('label'=>'Updated','index'=>'last_update', 'width'=>150, 'search'=>false))
//            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>

@endsection

@section('custom_css')


@endsection

@section('custom_js')


@endsection