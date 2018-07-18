<?php

Route::group(['prefix' => 'payment', 'namespace' => 'Payment'], function(){
    
    Route::get('/', [
        'as' => 'payment-bni-index',
        'uses' => 'PaymentController@index'
    ]);
    
    Route::post('/bni/notification', [
        'as' => 'payment-bni-notification',
        'uses' => 'PaymentController@bniNotification'
    ]);
    
});