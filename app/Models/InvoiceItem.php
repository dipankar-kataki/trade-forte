<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';
    protected $hidden = ['created_at', 'updated_at',"status","users_id"];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'items_added_by', 'id');
    }
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
            'users_id' => 'required|exists:users,id',
            'hsn_code' => 'required|string',
            'product_name' => 'required|string',
            'uqc' => 'required|string',
            'quantity' => 'required|integer',
            'packaging_description' => 'nullable|string',
            'net_weight_of_each_unit' => 'required|integer',
            'custom_column_name_1' => 'nullable|string',
            'custom_column_value_1' => 'nullable|string',
            'custom_column_name_2' => 'nullable|string',
            'custom_column_value_2' => 'nullable|string',
            'custom_column_name_3' => 'nullable|string',
            'custom_column_value_3' => 'nullable|string',
            'custom_column_name_4' => 'nullable|string',
            'custom_column_value_4' => 'nullable|string',
            'custom_column_name_5' => 'nullable|string',
            'custom_column_value_5' => 'nullable|string',
            'gst_rate' => 'nullable|integer',
            'unit_value' => 'nullable|integer',
            'cess_rate' => 'nullable|integer',
        ];
    }
    public static function updateRule()
    {
        return [
            'invoice_details_id' => 'nullable|exists:invoice_details,id',
            'users_id' => 'nullable|exists:users,id',
            'hsn_code' => 'nullable|string',
            'product_name' => 'nullable|string',
            'uqc' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'packaging_description' => 'nullable|string',
            'net_weight_of_each_unit' => 'nullable|integer',
            'custom_column_name_1' => 'nullable|string',
            'custom_column_value_1' => 'nullable|string',
            'custom_column_name_2' => 'nullable|string',
            'custom_column_value_2' => 'nullable|string',
            'custom_column_name_3' => 'nullable|string',
            'custom_column_value_3' => 'nullable|string',
            'custom_column_name_4' => 'nullable|string',
            'custom_column_value_4' => 'nullable|string',
            'custom_column_name_5' => 'nullable|string',
            'custom_column_value_5' => 'nullable|string',
            'gst_rate' => 'nullable|integer',
            'unit_value' => 'nullable|integer',
            'cess_rate' => 'nullable|integer',
        ];
    }
    public function invoiceDetails()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', 'invoice_id');
    }
    public function packagingDetails()
    {
        return $this->hasMany(PackagingDetail::class, 'invoice_item_id', 'id');
    }
}
