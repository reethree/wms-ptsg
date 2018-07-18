<?php
namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;
 
class InvoiceTablesRepository extends EloquentRepositoryAbstract {
 
    public function __construct($ModelRef, $request = null)
    {
        $Columns = array('*');

//        if(isset($request['tarif_id'])){
//            $Model = \DB::table($ModelRef)->where('tarif_id', $request['tarif_id']);
//        }else{
//            $Model = \DB::table($ModelRef);
//        }
        
        if($ModelRef == 'invoice_import'){
            $Model = \DB::table($ModelRef)
                    ->join('tmanifest', 'invoice_import.manifest_id', '=', 'tmanifest.TMANIFEST_PK');
        }elseif($ModelRef == 'invoice_tarif_consolidator'){
            $Model = \DB::table($ModelRef)
                    ->join('tconsolidator', 'invoice_tarif_consolidator.consolidator_id', '=', 'tconsolidator.TCONSOLIDATOR_PK');
        }elseif($ModelRef == 'invoice_tarif_nct'){
            $Model = InvoiceTarifNct::select('*');
        }elseif($ModelRef == 'invoice_nct'){
            $Model = InvoiceNct::select('*');
        }elseif($ModelRef == 'billing_template_item'){
            $Model = \DB::table($ModelRef)->where('billing_template_id',$request['templateid']);
        }elseif($ModelRef == 'billing_invoice'){
            $Model = \DB::table($ModelRef)->join('tmanifest', 'billing_invoice.manifest_id', '=', 'tmanifest.TMANIFEST_PK');
        
        }elseif($ModelRef == 'mechanic_rekap'){
            $Model = \DB::table($ModelRef)
                    ->join('tconsolidator', 'mechanic_rekap.consolidator_id', '=', 'tconsolidator.TCONSOLIDATOR_PK')
                    ->join('tcontainer', 'mechanic_rekap.container_id', '=', 'tcontainer.TCONTAINER_PK');
        }else{      
            $Model = \DB::table($ModelRef);
        }
        
        $this->Database = $Model;        
        $this->visibleColumns = $Columns; 
        $this->orderBy = array(array('id', 'asc'), array('name'));
    }
}