<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'items_added_by', 'id');
    }
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
            'hsn_code' => 'required|string',
            'product_name' => 'required|string',
            'cess_rate' => 'required|integer',
            'quantity' => 'required|integer',
            'gst_rate' => 'required|integer',
            'net_weight_of_each_unit' => 'required|integer',
            'uqc' => 'required|string',
        ];
    }

    public function invoiceDetails()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', 'invoice_id');
    }

}
