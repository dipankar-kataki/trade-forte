<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LorryItems extends Model
{
    use HasFactory;
    protected $table = 'lorry_items';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at',"status","users_id"];
    public static function createRule()
    {
        return [
            "date" => "required|date",
            "vehiclle_no" => "required|string",
            "trip" => "required|integer",
            "quantity" => "required|integer",
            "invoice_id" => "required|exists:invoice_details,id"
        ];
    }
    public static function updateRule()
    {
        return [
            "date" => "sometimes|date",
            "vehicle_no" => "sometimes|string",
            "trip" => "sometimes|integer",
            "quantity" => "sometimes|integer",
            "invoice_id" => "sometimes|exists:invoice_details,id"
        ];
    }
    public function lorryDetails()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', 'id');
    }
}
