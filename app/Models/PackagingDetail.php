<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingDetail extends Model
{
    use HasFactory;

    protected $table ='packaging_details';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }

    public function invoiceItem(){
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id', 'id');
    }
}
