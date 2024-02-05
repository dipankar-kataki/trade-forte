<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{

    use HasFactory;
    protected $table = 'lorries';
    protected $guarded = [];
    use HasFactory;

    protected $table = 'packaging_details';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public static function createRule()
    {
        return [
            'invoice_id' => 'required|exists:invoice_details,id',
            'invoice_item_id' => 'required|exists:invoice_items,id',
            'net_weight' => 'nullable|string',
            'gross_weight' => 'nullable|string',
            'each_box_weight' => 'nullable|numeric',
            'packaging_type' => 'required|string',
            'quantity' => 'required|integer',
            'vehicle_no' => 'nullable|string',
        ];
    }
    public static function updateRule()
    {
        return [
            'bank_accounts_id' => 'sometimes|exists:bank_accounts,id',
            'users_id' => 'sometimes|exists:users,id',
            'invoice_currency' => 'sometimes|string',
            'terms_of_payment' => 'sometimes|numeric',
        ];
    }


    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_accounts_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
