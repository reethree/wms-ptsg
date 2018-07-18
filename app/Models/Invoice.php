<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
//    protected $table = 'invoice_import';
    protected $table = 'billing_invoice';
    protected $primaryKey = 'id';

}
