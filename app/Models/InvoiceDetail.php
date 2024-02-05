<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';
    protected $with = ['exporters', 'consignees'];
    protected $guarded = [];

    public static function createRule()
    {
        return [
            'category' => 'required|string|in:domestic,export',
            'type' => 'required|string|in:with_payment_of_igst, without_payment_of_igst',
            'country_of_origin' => 'required|string',
            'country_of_export' => 'required|string',
            'country_of_destination' => 'required|string',
            "exporter_id" => "required|exists:exporters,id",
            "consignee_id" => "required|exists:consignees,id",
            'port_of_loading' => 'required|string',
            'port_of_destination' => 'required|string',
            "incoterm" => "required|string|in:EXW,FCA,FAS,FOB,CFR,CIF,CPT,CIP,DAP,DPU,DDP",
            "remarks" => "required|string",
            // 'freight' => 'required|string',
            // 'valid_upto' => 'required|date',
            // 'vehicle_no' => 'required|string',
            'invoice_date' => 'required|date',
            // 'eway_bill_id' => 'required|string',
            'po_contract_number' => 'required|string',
            'po_contract_date' => 'required|date',
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
        return $this->hasManyThrough(
            PackagingDetail::class,
            InvoiceItem::class,
            'invoice_id',
            'invoice_item_id',
            'id',
            'id'
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
}
