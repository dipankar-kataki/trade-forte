<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    protected $table = 'declarations';
    protected $hidden = ['created_at', 'updated_at',"status"];
    protected $guarded = [

    ];
    protected $casts = [
        "sequence" => "array"
    ];
    public static function createRule()
    {
        return [
            "invoice_details_id" => "required|exists:invoice_details,id",
            "declaration" => "required|string|max:65535",
        ];
    }
    public static function updateRule()
    {
        return [
            "declaration" => "sometimes|string|max:255",
        ];
    }
    public function getdeclaration()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_details_id', 'id');
    }
}
