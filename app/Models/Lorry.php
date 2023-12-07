<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model {
    use HasFactory;
    protected $table = 'lorries';
    public static function createRule() {
        return [
            "invoice_id" => "required|exists:invoice_details,id",
            "exporter_id" => "required|exists:exporters,id",
            "consignee_id" => "required|exists:consignees,id",
            "bank_id" => "required|exists:bank_accounts,id",
        ];
    }
    public static function updateRule() {
        return [
            "invoice_id" => "sometimes|exists:invoice_details,id",
            "exporter_id" => "sometimes|exists:exporters,id",
            "consignee_id" => "sometimes|exists:consignees,id",
            "bank_id" => "sometimes|exists:bank_accounts,id",
        ];
    }

}
