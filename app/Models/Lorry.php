<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lorry extends Model
{
    use HasFactory;
    protected $table = 'lorries';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at',"status","users_id"];
    public static function createRule()
    {
        return [
            "invoice_id" => "required|exists:invoice_details,id",
        ];
    }
    public static function updateRule()
    {
        return [
            "invoice_id" => "sometimes|exists:invoice_details,id",
        ];
    }
    public function invoice()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_id', "id");
    }
    public function exporter()
    {
        return $this->belongsTo(Exporter::class, 'exporter_id', 'id');
    }
    public function consignee()
    {
        return $this->belongsTo(Consignee::class, 'consignee_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(BankAccount::class, 'bank_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public function lorryItems()
    {
        return $this->hasMany(LorryItems::class, 'lorry_id');
    }
}
