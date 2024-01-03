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
            'invoice_id' => 'required|exists:invoice_details,id',
            'hsn_code' => 'nullable|string',
            'description' => 'required|string',
            'unit_type' => 'required|string',
            'unit_value' => 'required|integer',
            'weight' => 'required|integer',
            'net_weight' => 'nullable|integer',
            'total_value' => 'nullable|integer',
        ];
    }

    public function invoiceDetails()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', 'invoice_id');
    }

}
