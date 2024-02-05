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
            'import_export_code' => 'required|string',
            "exporter_id" => "required|exists:exporters,id",
            "consignee_id" => "required|exists:consignees,id",
            'auth_dealer_code' => 'required|string',
            'port_of_loading' => 'required|string',
            'port_of_destination' => 'required|string',
            "incoterm_cpt" => "required|string",
            "remarks" => "required|string",
            'freight' => 'required|string',
            'valid_upto' => 'required|date',
            'vehicle_no' => 'required|string',
            'insurance' => 'required|string',
            'buyer_no' => 'required|string',
            'invoice_date' => 'required|date',
            'eway_bill_id' => 'required|string',
            'category' => 'required|string|in:Domestic,Export',
            'type' => 'required|string|in:with_payment_of_igst, without_payment_of_igst',
            'p.o/contract_number' => 'required|string',
            'p.o/contract_date' => 'required|date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
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
