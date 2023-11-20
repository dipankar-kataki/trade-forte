<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'items_added_by', 'id');
    }

    public function invoiceDetails(){
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', 'id');
    }
}
