<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model
{
    use HasFactory;
    protected $table = 'lorry';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', "users_id"];
    public static function createRule()
    {
        return [
            "date" => "required|date",
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

    public function lorry_items()
    {
        return $this->hasMany(LorryItems::class, 'invoice_details_id',"id");
    }

}
