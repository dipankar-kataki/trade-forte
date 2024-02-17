<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{

    use HasFactory;
    protected $table = 'payments';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at',"status"];
    public static function createRule()
    {
        return [
            'bank_accounts_id' => 'sometimes|exists:bank_accounts,id',
            'invoice_details_id' => 'sometimes|exists:invoice_details,id',
            'invoice_currency' => 'sometimes|string',
            'terms_of_payment' => 'sometimes|string',
        ];
    }


    // public function bankAccount()
    // {
    //     return $this->belongsTo(BankAccount::class, 'bank_accounts_id');
    // }
    // public function users()
    // {
    //     return $this->belongsTo(User::class, 'users_id');
    // }
}
