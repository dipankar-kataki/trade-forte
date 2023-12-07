<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LorryItems extends Model {
    use HasFactory;
    protected $table = 'lorry_items';
    public static function createRule() {
        return [
            "date" => "required|date",
            "vehicle_no" => "required|string",
            "trip" => "required|integer",
            "quantity" => "required|integer",
        ];
    }
    public static function updateRule() {
        return [
            "date" => "sometimes|date",
            "vehicle_no" => "sometimes|string",
            "trip" => "sometimes|integer",
            "quantity" => "sometimes|integer",
        ];
    }
}
