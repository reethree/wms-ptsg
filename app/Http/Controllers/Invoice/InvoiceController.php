<?php

namespace App\Http\Controllers\Invoice;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    
    public function invoiceIndex()
    {
        if ( !$this->access->can('show.invoice.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice'
            ]
        ];        
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.index-invoice')->with($data);
    }
    
    public function releaseIndex()
    {
        if ( !$this->access->can('show.invoice.release.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index Invoice Release', 'slug' => 'show.invoice.release.index', 'description' => ''));
        
        $data['page_title'] = "Invoice Release";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice Release'
            ]
        ];        
        
//        $data['perusahaans'] = DBPerusahaan::select('TPERUSAHAAN_PK as id', 'NAMAPERUSAHAAN as name')->get();
        
        $data['templates'] = \DB::table('billing_template')->get();
        
        return view('invoice.index-release')->with($data);
    }
    
    public function invoiceEdit($id)
    {
        
        if ( !$this->access->can('edit.invoice.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Invoice";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-index'),
                'title' => 'Invoice'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['invoice'] = \App\Models\Invoice::find($id);
        $data['items'] = \App\Models\InvoiceItem::where('billing_invoice_id', $id)->get();
        $data['manifest'] = \App\Models\Manifest::find($data['invoice']->manifest_id);
//        $data['tarif'] = \App\Models\InvoiceTarif::where(array('consolidator_id' => $data['manifest']->TCONSOLIDATOR_FK, 'type' => $data['manifest']->INVOICE))->first();
//        $data['tarif'] = \App\Models\ConsolidatorTarif::where('TCONSOLIDATOR_FK', $data['manifest']->TCONSOLIDATOR_FK)->first();
//        $total = $data['invoice']->sub_total + $data['invoice']->ppn;
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total_amount))." Rupiah";
        
        return view('invoice.edit-invoice')->with($data);
    }
    
    public function invoiceDestroy($id)
    {
        \App\Models\Invoice::where('id', $id)->delete();
        \App\Models\InvoiceItem::where('billing_invoice_id', $id)->delete();
        return back()->with('success', 'Invoice has been deleted.'); 
    }
    
    public function invoicePrint($id)
    {
        $data['invoice'] = \App\Models\Invoice::find($id);
        $data['items'] = \App\Models\InvoiceItem::where('billing_invoice_id', $id)->get();
        $data['manifest'] = \App\Models\Manifest::find($data['invoice']->manifest_id);
//        $data['tarif'] = \App\Models\InvoiceTarif::where(array('consolidator_id' => $data['manifest']->TCONSOLIDATOR_FK, 'type' => $data['manifest']->INVOICE))->first();
//        $data['tarif'] = \App\Models\ConsolidatorTarif::where('TCONSOLIDATOR_FK', $data['manifest']->TCONSOLIDATOR_FK)->first();
//        $total = $data['invoice']->sub_total + $data['invoice']->ppn;
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total_amount))." Rupiah";
//        return view('print.bon-muat', $container);
        
//        switch ($type){
//            case 'bon-muat':
//                $pdf = \PDF::loadView('print.bon-muat', $data);        
//                break;
//            case 'surat-jalan':
//                $pdf = \PDF::loadView('print.surat-jalan', $data);
//                break;
//        }
        return view('print.invoice-ltt')->with($data);
        $pdf = \PDF::loadView('print.invoice', $data)->setPaper('a4');
        
        return $pdf->stream($data['invoice']->no_invoice.'-'.date('dmy').'.pdf');
    }
    
    public function invoiceRenew(Request $request)
    {
        $data_invoice = \App\Models\Invoice::find($request->invoice_id);
        $manifest = \App\Models\Manifest::find($data_invoice->manifest_id);  
        $template = \DB::table('billing_template')->find($data_invoice->template_id);
        $items = \DB::table('billing_template_item')->where(array('billing_template_id' => $template->id, 'template_type' => $data_invoice->template_type, 'active' => 'Y'))->get();
        
        $invoice = new \App\Models\Invoice;
        $invoice->manifest_id = $manifest->TMANIFEST_PK;
        $invoice->template_id = $template->id;
        $invoice->template_type = $data_invoice->template_type;
        $invoice->number = $data_invoice->number;
        $invoice->officer = $request->officer;
        $invoice->tgl_cetak = $request->tgl_cetak;
        $invoice->renew = 'Y';
        $invoice->renew_date = date('Y-m-d', strtotime($request->renew_date));

        // Perhitungan CBM
        $weight = $manifest->WEIGHT / 1000;
        $meas = $manifest->MEAS;
        $cbm = array($weight, $meas);

        if($template->min_meas > 0){            
//            $maxcbm = ceil(max($cbm) * 2) / 2;
            $maxcbm = ceil(max($cbm));               
        }else{
            $maxcbm = max($cbm);
        }
        if($maxcbm < $template->min_meas){ $maxcbm = $template->min_meas; }

        // Perhitungan Hari
        $dayby = $manifest->tglrelease;
        $date1 = date_create($dayby);
        $date2 = date_create(date('Y-m-d',strtotime($request->renew_date. '+1 days')));
        $diff = date_diff($date1, $date2);
        $days = $diff->format("%a");

        $invoice->weight = $manifest->WEIGHT;
        $invoice->meas = $manifest->MEAS;
        $invoice->cbm = $maxcbm;
        $invoice->days = $days;
        $invoice->uid = \Auth::getUser()->name;

        if($invoice->save()){
            $subtotal_amount = array();
            $total_tax = array();

            foreach ($items as $item):
                $invoice_item = new \App\Models\InvoiceItem;
                $invoice_item->billing_invoice_id = $invoice->id;
                $invoice_item->item_name = $item->name;
                $invoice_item->item_type = 'tmp';
                $item_qty = 1;
                $item_price = $item->price;

                if($item->type == 'Storage Flat'){
                    $item_qty = $days;
                }elseif($item->type == 'Storage Masa'){
                    // Perhitungan Masa
                    if($days < $item->day_start){
                        continue;
                    }else{
                        if($item->day_end > 0){
                            if($days >= $item->day_end){
                                $item_qty = ($item->day_end - $item->day_start)+1;
                            }else{
                                $item_qty = ($days - $item->day_start)+1;
                            }
                        }else{
                            $item_qty = ($days - $item->day_start)+1;
                        }
                    }
                }elseif(strripos($item->name, 'adm') !== false){
                    
                }else{
                    continue;
                }

                if($item->formula == 'X'){
                    if($item->type == 'Storage Flat' || $item->type == 'Storage Masa'){
                        $item_subtotal = ($maxcbm*$item_qty)*$item_price;
                    }else{
                        $item_subtotal = $maxcbm*$item_price;
                    }

                }else{
                    if($item->type == 'Storage Flat' || $item->type == 'Storage Masa'){
                        $item_subtotal = $item_price*$item_qty;
                    }else{
                        $item_subtotal = $item_price;
                    }   
                }  

                $invoice_item->item_cbm = $maxcbm;
                $invoice_item->item_qty = $item_qty;
                $invoice_item->item_amount = $item_price;
                $invoice_item->item_subtotal = $item_subtotal;
                $invoice_item->item_ppn = ($invoice_item->item_subtotal*$item->tax)/100; 
                $invoice_item->item_tax = $item->tax;

                $subtotal_amount[] = $invoice_item->item_subtotal;
                $total_tax[] = $invoice_item->item_ppn;

                $invoice_item->save();

            endforeach;
        }

        // Update Invoice
        $invoice->subtotal_amount = array_sum($subtotal_amount);
        $invoice->total_tax = array_sum($total_tax);
        $invoice->total_amount = $invoice->subtotal_amount+$invoice->total_tax;
        
        if($invoice->save()){
            return back()->with('success', 'Perpanjangan invoice No. '. $data_invoice->number .' berhasih dibuat.');
        }
        
        return back()->with('error', 'Terjadi kesalahan, silahkan ulangi lagi.');
    }
    
    public function invoicePrintRekap(Request $request)
    {
        $consolidator_id = $request->consolidator_id;
        $start = $request->tanggal.' 00:00:00';
        $end = date('Y-m-d', strtotime('+1 Day', strtotime($request->tanggal))).' 00:00:00';
        $type = $request->type;
        
        $data['consolidator'] = \App\Models\Consolidator::find($consolidator_id);
        $data['invoices'] = \App\Models\Invoice::select('*')
                ->join('tmanifest','invoice_import.manifest_id','=','tmanifest.TMANIFEST_PK')
                ->where('tmanifest.TCONSOLIDATOR_FK', $consolidator_id)
                ->where('tmanifest.tglrelease',$request->tanggal)
//                ->where('invoice_import.created_at','>=',$start)
//                ->where('invoice_import.created_at','<',$end)
                ->where('tmanifest.INVOICE', $type)
                ->get();
        
        if(count($data['invoices']) > 0):
            $sum_total = array();
            foreach ($data['invoices'] as $invoice):
                $sum_total[] = $invoice->sub_total;        
            endforeach;
            
            $data['sub_total'] = array_sum($sum_total);
            if(isset($request->free_ppn)):
                $data['ppn'] = 0;
            else:
                $data['ppn'] = $data['sub_total']*10/100;
            endif;
            $data['materai'] = ($data['sub_total'] > 1000000) ? '6000' : '3000';
            $data['total'] = $data['sub_total'] + $data['ppn'] + $data['materai'];           
            $data['terbilang'] = ucwords($this->terbilang($data['total']))." Rupiah";

            $pdf = \PDF::loadView('print.invoice-rekap', $data)->setPaper('legal');

            return $pdf->stream('Rekap Invoice '.date('d-m-Y').'-'.$data['consolidator']->NAMACONSOLIDATOR.'.pdf');
            
        endif;
        
        return back()->with('error', 'Data tidak ditemukan.')->withInput();
    }
    
    public function tarifIndex()
    {
        if ( !$this->access->can('show.tarif.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Daftar Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Daftar Tarif'
            ]
        ];        
        
        return view('invoice.index-tarif')->with($data);
    }
    
    public function tarifCreate()
    {
        if ( !$this->access->can('show.tarif.create') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Create Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => 'Create'
            ]
        ];         
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.create-tarif')->with($data);
    }

    public function tarifView($id)
    {
        if ( !$this->access->can('show.tarif.view') ) {
            return view('errors.no-access');
        }
        
        $tarif = \DB::table('invoice_tarif')->find($id);
        
        $data['page_title'] = "Daftar Item Tarif ".$tarif->type;
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => "Daftar Item Tarif ".$tarif->type
            ]
        ];        
        
        $data['tarif'] = $tarif;
        
        return view('invoice.view-tarif')->with($data);
    }
    
    public function tarifEdit($id)
    {
        if ( !$this->access->can('show.tarif.edit') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Tarif";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];         
        $data['tarif'] = \App\Models\InvoiceTarif::find($id);
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('invoice.update-tarif')->with($data);
    }
    
    public function tarifStore(Request $request)
    {
        if ( !$this->access->can('store.tarif.create') ) {
            return view('errors.no-access');
        }
        
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        $data['UID'] = \Auth::getUser()->name;
        
        $insert_id = \App\Models\InvoiceTarif::insertGetId($data);
        
        if($insert_id){
            return redirect()->route('invoice-tarif-index')->with('success', 'Tarif has been created.');
        }
        
        return back()->with('error', 'Tarif cannot create, please try again.')->withInput();
    }
    
    public function tarifUpdate(Request $request, $id)
    {
        if ( !$this->access->can('show.tarif.edit') ) {
            return view('errors.no-access');
        }
        
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        if(!isset($data['surcharge'])) { $data['surcharge'] = 0; }
        if(!isset($data['cbm'])) { $data['cbm'] = 0; }
        if(!isset($data['pembulatan'])) { $data['pembulatan'] = 0; }

        $update = \App\Models\InvoiceTarif::where('id', $id)->update($data);
        
        if($update){
            return redirect()->route('invoice-tarif-index')->with('success', 'Tarif has been updated.');
        }
        
        return back()->with('error', 'Tarif cannot update, please try again.')->withInput();
    }
    
    public function tarifDestroy($id)
    {
        \App\Models\InvoiceTarif::where('id', $id)->delete();
        return back()->with('success', 'Invoice tarif has been deleted.'); 
    }
    
    public function tarifItemEdit($id)
    {
        if ( !$this->access->can('show.tarif.item.edit') ) {
            return view('errors.no-access');
        }
        
        $tarif_item = \DB::table('invoice_tarif_item')->find($id);
        
        $data['page_title'] = "Edit Item Tarif ".$tarif_item->description;
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-tarif-index'),
                'title' => 'Daftar Tarif'
            ],
            [
                'action' => '',
                'title' => "Edit Item Tarif ".$tarif_item->description
            ]
        ];        
        
        $data['item'] = $tarif_item;
        
        return view('invoice.edit-tarif')->with($data);
    }
    
    public function tarifItemUpdate(Request $request, $id)
    {
        if ( !$this->access->can('update.tarif.item.edit') ) {
            return view('errors.no-access');
        }
        
        unset($request['_token']);
        
        //UPDATE TARIF
        $update = \DB::table('invoice_tarif_item')->where('id', $id)
            ->update($request->all());

        if($update){

            return back()->with('success', 'LCL Register has been updated.');                   
        }
        
        return back()->with('error', 'Something wrong, please try again.')->withInput();
    }
    
    
//    FCL INVOICE
    public function invoiceNctIndex()
    {
        if ( !$this->access->can('show.invoicenct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Invoice NCT1";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice NCT1'
            ]
        ];        

        return view('invoice.index-invoice-nct')->with($data);
    }
    
    public function invoiceNctEdit($id)
    {
        if ( !$this->access->can('edit.invoiceNct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Invoice NCT1";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('invoice-nct-index'),
                'title' => 'Invoice NCT1'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];
        
        $data['invoice'] = \App\Models\InvoiceNct::find($id);
        $data['penumpukan'] = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $data['invoice']->id)->get();
        $data['gerakan'] = \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $data['invoice']->id)->orderBy('lokasi_sandar', 'ASC')->get();
        $data['tarif'] = \App\Models\InvoiceTarifNct::get();
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total))." Rupiah";
        
        return view('invoice.edit-invoice-nct')->with($data);
    }
    
    public function invoiceNctDestroy($id)
    {
        \App\Models\InvoiceNct::where('id', $id)->delete();
        \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $id)->delete();
        \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $id)->delete();
        
        return back()->with('success', 'Invoice has been deleted.'); 
    }
    
    public function invoiceNctPrint($id)
    {
        $data['invoice'] = \App\Models\InvoiceNct::find($id);
        $data['penumpukan'] = \App\Models\InvoiceNctPenumpukan::where('invoice_nct_id', $data['invoice']->id)->get();
        $data['gerakan'] = \App\Models\InvoiceNctGerakan::where('invoice_nct_id', $data['invoice']->id)->orderBy('lokasi_sandar', 'ASC')->get();
        $data['tarif'] = \App\Models\InvoiceTarifNct::get();
        $data['terbilang'] = ucwords($this->terbilang($data['invoice']->total))." Rupiah";
//        return view('print.invoice-nct')->with($data);
        $pdf = \PDF::loadView('print.invoice-nct', $data)->setPaper('legal');
        
        return $pdf->stream($data['invoice']->no_invoice.'.pdf');
    }
    
    public function tarifNctIndex()
    {
        if ( !$this->access->can('show.tarifnct.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Daftar Tarif NCT1";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Daftar Tarif NCT1'
            ]
        ];        
        
        return view('invoice.index-tarif-nct')->with($data);
    }
    
    public function releaseNctIndex()
    {
        if ( !$this->access->can('show.invoice.releasenct.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index Invoice Release NCT1', 'slug' => 'show.invoice.releasenct.index', 'description' => ''));
        
        $data['page_title'] = "Invoice Release FCL";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Invoice Release FCL'
            ]
        ];        
        
//        $data['perusahaans'] = DBPerusahaan::select('TPERUSAHAAN_PK as id', 'NAMAPERUSAHAAN as name')->get();
        
        return view('invoice.index-release-fcl')->with($data);
    }
    
    public function addCustomItem(Request $request)
    {

        $invoice = \App\Models\Invoice::find($request->invoice_id);
        
        if($invoice){
            $invoice_item = new \App\Models\InvoiceItem;
            $invoice_item->billing_invoice_id = $invoice->id;
            $invoice_item->item_name = $request->name;
            $invoice_item->item_qty = $request->qty;
            $invoice_item->item_amount = $request->price;
            $invoice_item->item_subtotal = $invoice_item->item_amount*$invoice_item->item_qty;
            $invoice_item->item_tax = $request->tax;
            $invoice_item->item_ppn = ($invoice_item->item_subtotal*$request->tax)/100;
            $invoice_item->item_cbm = 0;
            $invoice_item->item_type = 'custom';
            
            if($invoice_item->save()){
                // Update Invoice
                $invoice->subtotal_amount = $invoice->subtotal_amount+$invoice_item->item_subtotal;
                $invoice->total_tax = $invoice->total_tax+$invoice_item->item_ppn;
                $invoice->total_amount = $invoice->subtotal_amount+$invoice->total_tax;

                if($invoice->save()){
                    return back()->with('success', 'No. Invoice '.$invoice->number.', item berhasih ditambah.');
                }
                
                return back()->with('error', 'Item berhasil di tambah, tetapi invoice tidak berubah.');
            }
            
            return back()->with('error', 'Tidak dapat menambah item invoice.');
            
        }
        
        return back()->with('error', 'Something went wrong, please try again later.');
        
    }
    
}
