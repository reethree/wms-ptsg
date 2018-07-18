<?php

Route::group(['prefix' => 'invoice', 'namespace' => 'Invoice'], function(){
    
    Route::get('/', [
        'as' => 'invoice-index',
        'uses' => 'InvoiceController@invoiceIndex'
    ]);
    Route::post('/grid-data', function()
    {
//        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('invoice_import',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('billing_invoice',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });   
    Route::get('/edit/{id}', [
        'as' => 'invoice-edit',
        'uses' => 'InvoiceController@invoiceEdit'
    ]);
    Route::get('/delete/{id}', [
        'as' => 'invoice-delete',
        'uses' => 'InvoiceController@invoiceDestroy'
    ]);
    Route::get('/print/{id}', [
        'as' => 'invoice-print',
        'uses' => 'InvoiceController@invoicePrint'
    ]);
    Route::post('/print/rekap', [
       'as' => 'invoice-print-rekap',
        'uses' => 'InvoiceController@invoicePrintRekap'
    ]);
    Route::post('/renew', [
        'as' => 'invoice-renew',
        'uses'=> 'InvoiceController@invoiceRenew'
    ]);
    
    // RELEASE INVOICE
    Route::get('/release', [
        'as' => 'invoice-release-index',
        'uses' => 'InvoiceController@releaseIndex'
    ]);
    
    // TARIF
    Route::get('/tarif', [
        'as' => 'invoice-tarif-index',
        'uses' => 'InvoiceController@tarifIndex'
    ]);
    Route::get('/tarif/create', [
        'as' => 'invoice-tarif-create',
        'uses' => 'InvoiceController@tarifCreate'
    ]);
    Route::post('/tarif/create', [
        'as' => 'invoice-tarif-store',
        'uses' => 'InvoiceController@tarifStore'
    ]);
    Route::get('/tarif/view/{id}', [
        'as' => 'invoice-tarif-view',
        'uses' => 'InvoiceController@tarifView'
    ]);
    Route::get('/tarif/edit/{id}', [
        'as' => 'invoice-tarif-edit',
        'uses' => 'InvoiceController@tarifEdit'
    ]);
    Route::post('/tarif/edit/{id}', [
        'as' => 'invoice-tarif-update',
        'uses' => 'InvoiceController@tarifUpdate'
    ]);
    Route::get('/tarif/delete/{id}', [
       'as' => 'invoice-tarif-delete',
        'uses' => 'InvoiceController@tarifDestroy'
    ]);
    Route::get('/tarif/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('invoice_tarif_consolidator',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    
    Route::get('/tarif/item', [
        'as' => 'invoice-tarif-item-index',
        'uses' => 'InvoiceController@tarifItemIndex'
    ]);
    Route::get('/tarif/item/edit/{id}', [
        'as' => 'invoice-tarif-item-edit',
        'uses' => 'InvoiceController@tarifItemEdit'
    ]);
    Route::post('/tarif/item/edit/{id}', [
        'as' => 'invoice-tarif-item-update',
        'uses' => 'InvoiceController@tarifItemUpdate'
    ]);
    Route::get('/tarif/item/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('invoice_tarif_item',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    
    Route::post('/custom/item/add', [
        'as' => 'invoice-custom-item-add',
        'uses' => 'InvoiceController@addCustomItem'
    ]);

    // FCL NCT1
    Route::group(['prefix' => 'fcl'], function(){
        
        Route::get('/', [
            'as' => 'invoice-nct-index',
            'uses' => 'InvoiceController@invoiceNctIndex'
        ]);
        
        Route::post('/grid-data', function()
        {
            GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('invoice_nct',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
        }); 
        
        Route::get('/edit/{id}', [
            'as' => 'invoice-nct-edit',
            'uses' => 'InvoiceController@invoiceNctEdit'
        ]);
        
        Route::get('/delete/{id}', [
            'as' => 'invoice-nct-delete',
            'uses' => 'InvoiceController@invoiceNctDestroy'
        ]);
        
        Route::get('/print/{id}', [
        'as' => 'invoice-nct-print',
        'uses' => 'InvoiceController@invoiceNctPrint'
    ]);
        
        // TARIF
        Route::get('/tarif', [
            'as' => 'invoice-tarif-nct-index',
            'uses' => 'InvoiceController@tarifNctIndex'
        ]);
        
        Route::get('/tarif/grid-data', function()
        {
            GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('invoice_tarif_nct',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
        });
        
        // RELEASE INVOICE
        Route::get('/release', [
            'as' => 'invoice-release-nct-index',
            'uses' => 'InvoiceController@releaseNctIndex'
        ]);

    });
    
    
});

Route::group(['prefix' => 'mechanic', 'namespace' => 'Invoice'], function(){
    Route::get('/print/{id}', [
        'as' => 'mechanic-print',
        'uses' => 'MechanicController@printRekap'
    ]);
    
    Route::get('/tarif', [
        'as' => 'mechanic-tarif',
        'uses' => 'MechanicController@tarifIndex'
    ]);
    Route::post('/tarif/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('mechanic_tarif',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::get('/tarif/create', [
        'as' => 'mechanic-tarif-create',
        'uses' => 'MechanicController@tarifCreate'
    ]);    
    Route::post('/tarif/create', [
        'as' => 'mechanic-tarif-store',
        'uses' => 'MechanicController@tarifStore'
    ]);
    Route::get('/tarif/edit/{id}', [
        'as' => 'mechanic-tarif-edit',
        'uses' => 'MechanicController@tarifEdit'
    ]);    
    Route::post('/tarif/update/{id}', [
        'as' => 'mechanic-tarif-update',
        'uses' => 'MechanicController@tarifUpdate'
    ]);
    Route::get('/tarif/delete/{id}', [
        'as' => 'mechanic-tarif-delete',
        'uses' => 'MechanicController@tarifDelete'
    ]); 
    
    Route::get('/container', [
        'as' => 'mechanic-container',
        'uses' => 'MechanicController@indexContainer'
    ]);
    
    Route::get('/rekap', [
        'as' => 'mechanic-rekap',
        'uses' => 'MechanicController@indexRekap'
    ]);
    Route::post('/rekap/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('mechanic_rekap',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    Route::post('/rekap/create', [
        'as' => 'mechanic-rekap-create',
        'uses' => 'MechanicController@createRekap'
    ]);
    Route::post('/rekap/update', [
        'as' => 'mechanic-rekap-update',
        'uses' => 'MechanicController@updateRekap'
    ]);
    Route::get('/rekap/detail/{id}', [
        'as' => 'mechanic-rekap-detail',
        'uses' => 'MechanicController@detailRekap'
    ]);
    Route::get('/rekap/delete/{id}', [
        'as' => 'mechanic-rekap-delete',
        'uses' => 'MechanicController@deleteRekap'
    ]);
    Route::post('/rekap/custom/item/add', [
        'as' => 'mechanic-custom-item-add',
        'uses' => 'MechanicController@addCustomItemRekap'
    ]);
    Route::get('/rekap/custom/item/remove/{id}', [
        'as' => 'mechanic-custom-item-remove',
        'uses' => 'MechanicController@removeCustomItemRekap'
    ]);
});

Route::group(['prefix' => 'billing', 'namespace' => 'Invoice'], function(){
    
    // Template
    Route::get('/template', [
        'as' => 'billing-template',
        'uses' => 'BillingController@template'
    ]);
    
    Route::post('/template/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('billing_template',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    
    Route::get('/template/create', [
        'as' => 'billing-template-create',
        'uses' => 'BillingController@templateCreate'
    ]);
    
    Route::post('/template/create', [
        'as' => 'billing-template-store',
        'uses' => 'BillingController@templateStore'
    ]);
    
    Route::get('/template/edit/{id}', [
        'as' => 'billing-template-edit',
        'uses' => 'BillingController@templateEdit'
    ]);
    
    Route::post('/template/edit/{id}', [
        'as' => 'billing-template-update',
        'uses' => 'BillingController@templateUpdate'
    ]);
    
    // ITEM
    Route::get('/template/item', [
        'as' => 'billing-template-item',
        'uses' => 'BillingController@itemTemplate'
    ]);
    
    Route::post('/template/item/create', [
        'as' => 'billing-template-item-store',
        'uses' => 'BillingController@itemTemplateStore'
    ]);
        
    Route::post('/template/item/edit/{id}', [
        'as' => 'billing-template-item-update',
        'uses' => 'BillingController@itemTemplateUpdate'
    ]);
    
    Route::get('/template/item/delete/{id}', [
        'as' => 'billing-template-item-destroy',
        'uses' => 'BillingController@itemTemplateDestroy'
    ]);
    
    Route::post('/template/item/grid-data', function()
    {
        GridEncoder::encodeRequestedData(new \App\Models\InvoiceTablesRepository('billing_template_item',Illuminate\Support\Facades\Request::all()) ,Illuminate\Support\Facades\Request::all());
    });
    
    Route::get('/template/delete/{id}', [
        'as' => 'billing-template-delete',
        'uses' => 'BillingController@templateDelete'
    ]);
    
});