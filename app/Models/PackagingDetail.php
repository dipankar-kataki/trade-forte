<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingDetail extends Model {
    use HasFactory;

    protected $table = 'packaging_details';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public static function createRule() {
        return [
            'invoice_item_id' => 'required|exists:invoice_items,id',
            'details_added_by' => 'exists:users,id',
            'description' => 'nullable|string',
            'each_box_weight' => 'nullable|numeric',
            'packaging_type' => 'required|string',
            'quantity' => 'required|integer',
            'vehicle_no' => 'nullable|string',
        ];
    }

    public function invoiceItem() {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id', 'id');
    }
}
