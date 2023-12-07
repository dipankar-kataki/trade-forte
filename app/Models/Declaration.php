<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model {
    use HasFactory;

    protected $table = 'declarations';
    protected $guarded = [

    ];
    protected $casts = [
        "sequence" => "array"
    ];
    public static function createRule() {
        return [
            "declaration" => "required|string",
            "invoice_id" => "required|exists:invoice_details,id",
        ];
    }
    public static function updateRule() {
        return [
            "declaration" => "sometimes|string|max:255",
        ];
    }
}
