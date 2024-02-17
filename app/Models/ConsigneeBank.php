<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsigneeBank extends Model
{
    use HasFactory;

    protected $table = 'consignees_bank_accounts';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
    public static function createRule()
    {
        return [
            'consignee_id' => 'required|exists:consignees,id',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'forex_account_name' => 'required|string|max:255',
            'forex_account_no' => 'required|string|max:255',
            'swift_code' => 'nullable|string|max:255',
        ];
    }
    public static function updateRule()
    {
        return [
            'consignees_id' => 'sometimes|exists:consignees,id',
            'bank_name' => 'sometimes|string|max:255',
            'branch_name' => 'sometimes|string|max:255',
            'forex_account_name' => 'sometimes|string|max:255',
            'forex_account_no' => 'sometimes|string|max:255',
            'swift_code' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',

        ];
    }

    public function exporter(
    ) {
        return $this->belongsTo(Consignee::class, 'consignees_id', 'id');
    }
}
