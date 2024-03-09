<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model
{
    use HasFactory;

    protected $table = 'lorry';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'users_id'];

    public static function createRule()
    {
        return [
            "date" => "required|date",
            "uqc" => "required|string",
            "total_trips" => "required|integer",
            "total_quantity" => "required|integer",
            "exporter_id" => "required|exists:exporters,id",
            "exporter_address_id" => "required|exists:exporter_address,id",
            "consignee_id" => "required|exists:consignees,id",
        ];
    }

    public static function updateRule()
    {
        return [
            "date" => "sometimes|date",
            "uqc" => "sometimes|string",
            "total_trips" => "sometimes|integer",
            "exporter_id" => "sometimes|exists:exporters,id",
            "exporter_address_id" => "sometimes|exists:exporter_address,id",
            "consignee_id" => "sometimes|exists:consignees,id",
        ];
    }

    public function lorry_items()
    {
        return $this->hasMany(LorryItems::class, 'lorry_id', 'id');
    }

    public function lorry_invoices()
    {
        return $this->hasMany(LorryInvoices::class, 'lorry_id', 'id');
    }

    public function exporter()
    {
        return $this->belongsTo(Exporter::class, 'exporter_id', 'id');
    }

    public function consignee()
    {
        return $this->belongsTo(Consignee::class, 'consignee_id', 'id');
    }

    public function exporter_address()
    {
        return $this->belongsTo(ExporterAddress::class, 'exporter_address_id', 'id');
    }
}
