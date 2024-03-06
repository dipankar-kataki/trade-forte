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
            "total_trips" => "required|integer",
            "total_quantity" => "required|integer",
        ];
    }
    public static function updateRule()
    {
        return [
            "date" => "sometimes|date",
            "uqc" => "sometimes|string", 
            "total_trips" => "sometimes|integer",
        ];
    }

    public function lorry_items()
    {
        return $this->hasMany(LorryItems::class, 'lorry_id', "id");
    }
    public function lorry_invoices()
    {
        return $this->hasMany(LorryInvoices::class, 'lorry_id', "id");
    }
}
