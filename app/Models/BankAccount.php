<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at',"status", "exporter_id"];
    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
    public static function createRule()
    {
        return [
            'exporter_id' => 'required|exists:exporters,id',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'auth_dealer_code' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'swift_code' => 'required|string|max:255',
        ];
    }
    public static function updateRule()
    {
        return [
            'id' => 'required|exists:bank_accounts,id',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'swift_code' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
            'auth_dealer_code' => 'required|string|max:255',
        ];
    }

    public function exporter(
    ) {
        return $this->belongsTo(Exporter::class, 'exporter_id', 'id');
    }
}
