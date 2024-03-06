<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LorryInvoices extends Model
{
    use HasFactory;
    protected $table = 'lorry_invoices';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', "users_id"];
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
        ];
    }
    public static function updateRule()
    {
        return [
            'invoice_details_id' => 'sometimes|exists:invoice_details,id',
        ];
    }
    public function lorry()
    {
        return $this->belongsTo(Lorry::class, 'lorry_id', "id");
    }

}
