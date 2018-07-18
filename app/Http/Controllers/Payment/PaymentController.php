<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    
    public function index()
    {
        if ( !$this->access->can('show.payment.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "BNI E-Collection";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'BNI E-Collection'
            ]
        ];        
        
        return view('payment.index-bni')->with($data);
    }
    
    public function bniNotification()
    {
        return;
    }

}
