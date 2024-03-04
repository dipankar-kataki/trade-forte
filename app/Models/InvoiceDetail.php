<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';
    protected $with = ['exporters', 'consignees'];
    protected $hidden = ['created_at', 'updated_at', "status", "users_id"];
    protected $guarded = [];

    public static function createFirstRule()
    {
        return [
            'category' => 'required|string|in:domestic,export',
            'type' => 'nullable|string|in:with_payment_of_igst,without_payment_of_igst',
            'country_of_origin' => 'required|string',
            'country_of_export' => 'required|string',
            'country_of_destination' => 'required|string',
            "exporter_id" => "required|exists:exporters,id",
            "exporter_address_id" => "required|exists:exporter_address,id",
            "consignee_id" => "required|exists:consignees,id",
            'port_of_loading' => 'required|string',
            'port_of_destination' => 'required|string',
            "incoterm" => "required|string|in:EXW,FCA,FAS,FOB,CFR,CIF,CPT,CIP,DAP,DPU,DDP",
            "remarks" => "required|string",
            'invoice_date' => 'required|date',
            'po_contract_number' => 'required|string',
            'po_contract_date' => 'required|date',
        ];
    }
    public static function createSecondRule()
    {
        return [
            'bank_accounts_id' => 'sometimes|exists:bank_accounts,id',
            'invoice_currency' => 'sometimes|string',
            'terms_of_payment' => 'sometimes|string',
        ];
    }

    public static function createThirdRule()
    {
        return [
            'mode_of_transport' => 'required|string',
            'bl_awb_lr_no' => 'required|string',
            'bl_awb_lr_date' => 'required|date',
            'transporter_name' => 'required|string',
            'vehicle_vessel_flight_no' => 'required|string',
            'challan_number' => 'required|string',
            'challan_date' => 'required|date',
            'eway_bill_no' => 'required|string',
            'eway_bill_date' => 'required|date',
            'pre_carriage_by' => 'required|string',
            'place_of_pre_carriage' => 'required|string',
        ];
    }
    public static function createFourthRule()
    {
        return [
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
    public static function createFifthRule()
    {
        return [
            "declaration" => "required|string|max:65535",
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_details_id', 'id');
    }

    public function exporters()
    {
        return $this->belongsTo(Exporter::class, 'exporter_id', 'id');
    }

    public function consignees()
    {
        return $this->belongsTo(Consignee::class, 'consignee_id', 'id');
    }

    public function packagingDetails()
    {
        return $this->hasMany(
            PackagingDetail::class,
            'invoice_details_id',
            'id',
        );
    }
    public function payments()
    {
        return $this->hasOne(Payments::class, 'invoice_details_id');
    }
    public function transport()
    {
        return $this->hasOne(Transportation::class, 'invoice_details_id');
    }
    public function declarations()
    {
        return $this->hasOne(Declaration::class, 'invoice_details_id');
    }
    public function exporter_address()
    {
        return $this->belongsTo(ExporterAddress::class, 'exporter_address_id', "id");
    }
    public function shipping_address()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_id', "id");
    }

    public function lorry_items()
    {
        return $this->hasMany(LorryItems::class, 'invoice_details_id', "id");
    }
    public function lorry_details()
    {
        return $this->hasOne(Lorry::class, 'invoice_details_id', 'id');
    }
    public function exporter_bank()
    {
        return $this->belongsTo(BankAccount::class, 'bank_accounts', "id");
    }
    public function consignee_bank()
    {
        return $this->belongsTo(ConsigneeBank::class, 'bank_accounts_id', "id");
    }
}
