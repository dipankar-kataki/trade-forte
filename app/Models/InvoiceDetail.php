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
            'country_of_origin' => 'required|string',
            'country_of_export' => 'required|string',
            'country_of_destination' => 'required|string',
            'import_export_code' => 'nullable|string',
            "exporter_id" => "required|exists:exporters,id",
            "consignee_id" => "required|exists:consignees,id",
            'auth_dealer_code' => 'nullable|string',
            'port_of_loading' => 'nullable|string',
            'port_of_destination' => 'nullable|string',
            "incoterm_cpt" => "nullable|string",
            "buyer_order_no" => "nullable|string",
            "terms_of_payment" => "nullable|string",
            'freight' => 'nullable|string',
            'valid_upto' => 'nullable|date',
            'vehicle_no' => 'nullable|string',
            'insurance' => 'nullable|string',
            'buyer_no' => 'nullable|string',
            'invoice_date' => 'nullable|date',
            'eway_bill_id' => 'nullable|string',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
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
    public function declarations()
    {
        return $this->hasMany(Declaration::class, 'invoice_id');
    }
}
