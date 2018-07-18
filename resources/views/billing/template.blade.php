@extends('layout')

@section('content')

<script>
    
    var grid = $("#billingTemplateGrid"), headerRow, rowHight, resizeSpanHeight;

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
        var ids = jQuery("#billingTemplateGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            edt = '<a href="{{ route("billing-template-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
            del = '<a href="{{ route("billing-template-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
            jQuery("#billingTemplateGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del}); 
        } 
    }
    
</script>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Billing Template Lists</h3>
        <div class="box-tools">
            <a href="{{route('billing-template-create')}}" class="btn btn-block btn-info btn-sm"><i class="fa fa-plus"></i> Add New</a>
        </div>
    </div>
    <div class="box-body table-responsive">
        {{
            GridRender::setGridId("billingTemplateGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/billing/template/grid-data?_token='.csrf_token()))
            ->setFileProperty('title', 'Billing Template') //Laravel Excel File Property
            ->setFileProperty('creator', 'Reza') //Laravel Excel File Property
            ->setSheetProperty('fitToPage', true) //Laravel Excel Sheet Property
            ->setSheetProperty('fitToHeight', true)
            ->setGridOption('rowNum', 20)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','created_at')
            ->setGridOption('rownumbers', true)
            ->setGridOption('height', '350')
            ->setGridOption('rowList',array(20,50,100))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->setGridEvent('gridComplete', 'gridCompleteEvent')
            ->addColumn(array('label'=>'Action','index'=>'action', 'width'=>80, 'search'=>false, 'sortable'=>false, 'align'=>'center'))
            ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
            ->addColumn(array('label'=>'Type','index'=>'type','width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Template Name','index'=>'name','width'=>220))
            ->addColumn(array('label'=>'Consolidator ID','index'=>'consolidator_id','width'=>80,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Consolidator','index'=>'consolidator_name','width'=>250))
            ->addColumn(array('label'=>'Min. Meas','index'=>'min_meas','width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'Rounding','index'=>'rounding','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Day By','index'=>'day_by','width'=>80,'align'=>'center')) 
            ->addColumn(array('label'=>'Warehouse','index'=>'warehouse','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Forwarder','index'=>'forwarder','width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Recap Tax','index'=>'recap_tax','width'=>80,'hidden'=>true))
            ->addColumn(array('label'=>'Created','index'=>'created_at','width'=>140,'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>

@endsection