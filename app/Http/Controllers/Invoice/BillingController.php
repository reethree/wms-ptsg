<?php

namespace App\Http\Controllers\Invoice;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    
    public function index()
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

        return view('billing.index')->with($data);
    }
    
    public function template()
    {
        if ( !$this->access->can('show.billing.template.index') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Index Billing Template', 'slug' => 'show.billing.template.index', 'description' => ''));
        
        $data['page_title'] = "Billing Template";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Billing Template'
            ]
        ];        

        return view('billing.template')->with($data);
    }
    
    public function templateCreate()
    {
        if ( !$this->access->can('show.billing.template.create') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Create Billing Template', 'slug' => 'show.billing.template.create', 'description' => ''));
        
        $data['page_title'] = "Create Billing Template";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('billing-template'),
                'title' => 'Billing Template'
            ],
            [
                'action' => '',
                'title' => 'Create'
            ]
        ];         
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('billing.create-template')->with($data);
    }
    
    public function templateStore(Request $request)
    {
//        return $request->all();
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token','rounding_value']);
        $data['uid'] = \Auth::getUser()->name;
        
        $consolidator = \App\Models\Consolidator::find($request->consolidator_id);
        $data['consolidator_name'] = $consolidator->NAMACONSOLIDATOR;
//        $data['rounding'] = isset($request->rounding) ? 'Y' : 'N';
        $data['warehouse'] = isset($request->warehouse) ? 'Y' : 'N';
        $data['forwarder'] = isset($request->forwarder) ? 'Y' : 'N';
        
        if(isset($request->rounding)){
            $data['rounding'] = $request->rounding_value;
        }else{
            $data['rounding'] = 'N';
        }
        
        $insert_id = \DB::table('billing_template')->insertGetId($data);
        
        if($insert_id){
            return redirect()->route('billing-template-edit', $insert_id)->with('success', 'Billing template has been created.');
        }
        
        return back()->with('error', 'Billing template cannot create, please try again.')->withInput();
    }
    
    public function templateEdit($id)
    {
        if ( !$this->access->can('show.billing.template.edit') ) {
            return view('errors.no-access');
        }
        
        // Create Roles Access
        $this->insertRoleAccess(array('name' => 'Edit Billing Template', 'slug' => 'show.billing.template.edit', 'description' => ''));
        
        $data['page_title'] = "Edit Billing Template";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('billing-template'),
                'title' => 'Billing Template'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];         
        
        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        $data['template'] = \DB::table('billing_template')->find($id);
        
        return view('billing.edit-template')->with($data);
    }

    public function templateUpdate(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token', 'rounding_value']);
        $data['updated_at'] = date('Y-m-d');
        $data['uid'] = \Auth::getUser()->name;
        
        $consolidator = \App\Models\Consolidator::find($request->consolidator_id);
        $data['consolidator_name'] = $consolidator->NAMACONSOLIDATOR;
//        $data['rounding'] = isset($request->rounding) ? 'Y' : 'N';
        $data['warehouse'] = isset($request->warehouse) ? 'Y' : 'N';
        $data['forwarder'] = isset($request->forwarder) ? 'Y' : 'N';
        
        if(isset($request->rounding)){
            $data['rounding'] = $request->rounding_value;
        }else{
            $data['rounding'] = 'N';
        }
        
        $update = \DB::table('billing_template')->where('id', $id)->update($data);
        
        if($update){
            return back()->with('success', 'Billing template has been updated.');
        }
        
        return back()->with('error', 'Billing template cannot create, please try again.')->withInput();
    }
    
    public function templateDelete($id)
    {
        $delete = \DB::table('billing_template')->where('id', $id)->delete();
        if($delete){
            // Delete Item
            \DB::table('billing_template_item')->where('billing_template_id',$id)->delete();
            return back()->with('success', 'Billing template has been deleted.');
        }
        return back()->with('error', 'Billing template cannot delete, please try again.')->withInput();
    }
    
    public function itemTemplate()
    {
        
    }
    
    public function itemTemplateStore(Request $request)
    {
        $data = $request->json()->all(); 
        $item_name = $data['item_name'];
        
        if(empty($item_name)){
            return json_encode(array('success' => false, 'message' => 'Please insert item name.'));
        }
//        if(empty($data['price'])){
//            return json_encode(array('success' => false, 'message' => 'Please insert item price.'));
//        }
        
        unset($data['id'], $data['item_name'], $data['_token']);
        
        $data['name'] = $item_name;
        $data['uid'] = \Auth::getUser()->name;
        
        $insert_id = \DB::table('billing_template_item')->insertGetId($data);
        
        if($insert_id){

            return json_encode(array('success' => true, 'message' => 'Item successfully saved!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function itemTemplateUpdate(Request $request, $id)
    {
        $data = $request->json()->all(); 
        $item_id = $data['id'];
        $item_name = $data['item_name'];
        unset($data['id'], $data['item_name'], $data['_token']);
        
        $data['name'] = $item_name;
        $data['uid'] = \Auth::getUser()->name;
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $update = \DB::table('billing_template_item')->where('id', $item_id)->update($data);
        
        if($update){

            return json_encode(array('success' => true, 'message' => 'Item successfully Updated!'));
        }
        
        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }
    
    public function itemTemplateDestroy($id)
    {
        try
        {           
            \DB::table('billing_template_item')->where('id',$id)->delete();
        }
        catch (Exception $e)
        {
          return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Item successfully deleted!'));
    }
    
}
