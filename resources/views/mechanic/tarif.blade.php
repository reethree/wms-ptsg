@extends('layout')

@section('content')

<script>
    
    var grid = $("#tarifMechanicGrid"), headerRow, rowHight, resizeSpanHeight;

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
        var ids = jQuery("#tarifMechanicGrid").jqGrid('getDataIDs'),
            edt = '',
            del = ''; 
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            edt = '<a href="{{ route("mechanic-tarif-edit",'') }}/'+cl+'"><i class="fa fa-pencil"></i></a> ';
            del = '<a href="{{ route("mechanic-tarif-delete",'') }}/'+cl+'" onclick="if (confirm(\'Are You Sure ?\')){return true; }else{return false; };"><i class="fa fa-close"></i></a>';
            jQuery("#tarifMechanicGrid").jqGrid('setRowData',ids[i],{action:edt+' '+del}); 
        } 
    }
    
</script>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Lists Tarif Mechanic</h3>
        <div class="box-tools">
            <a href="{{route('mechanic-tarif-create')}}" class="btn btn-block btn-info btn-sm"><i class="fa fa-plus"></i> Add New</a>
        </div>
    </div>
    <div class="box-body table-responsive">
        {{
            GridRender::setGridId("tarifMechanicGrid")
            ->enableFilterToolbar()
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/mechanic/tarif/grid-data?_token='.csrf_token()))
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
            ->addColumn(array('label'=>'Consolidator ID','index'=>'consolidator_id','width'=>80,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Consolidator','index'=>'consolidator_name','width'=>300))
            ->addColumn(array('label'=>'Tarif 1','index'=>'tarif1','width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Tarif 2','index'=>'tarif2','width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Tarif 3','index'=>'tarif3','width'=>120,'align'=>'center')) 
            ->addColumn(array('label'=>'Created','index'=>'created_at','width'=>140,'align'=>'center'))
            ->renderGrid()
        }}
    </div>
</div>

@endsection