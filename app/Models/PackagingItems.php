<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingItems extends Model
{
    use HasFactory;

    protected $table = 'packaging_items';

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', "status", "users_id"];
    public function user()
    {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
            'net_weight' => 'nullable|string',
            'total_gross_weight' => 'nullable|string',
            'each_box_weight' => 'nullable|numeric',
            'packaging_type' => 'required|string',
            'quantity' => 'required|integer',
        ];
    }
    public static function updateRule()
    {
        return [
            'invoice_details_id' => 'sometimes|exists:invoice_details,id',
            'description' => 'sometimes|string',
            'each_box_weight' => 'sometimes|numeric',
            'packaging_type' => 'sometimes|string',
            'quantity' => 'sometimes|integer',
            'vehicle_no' => 'sometimes|string',
        ];
    }


    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }
}
