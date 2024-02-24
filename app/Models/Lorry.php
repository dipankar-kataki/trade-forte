<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model
{
    use HasFactory;
    protected $table = 'lorry_items';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', "users_id"];
    public static function createRule()
    {
        return [
            "date" => "required|date",
            "total_quantity" => "required|integer",
            "uqc" => "required|string",
            "invoice_details_id" => "required|exists:invoice_details,id"
        ];
    }
    public static function updateRule()
    {
        return [
            "date" => "sometimes|date",
            "total_quantity" => "sometimes|integer",
            "uqc" => "required|string",
            "invoice_details_id" => "sometimes|exists:invoice_details,id"
        ];
    }
    public function lorryDetails()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_details_id', 'id');
    }
}
