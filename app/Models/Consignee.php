<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    use HasFactory;

    protected $table = 'consignees';

    protected $guarded = [];
    public static function createRule()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'pin_code' => 'nullable|string|max:255',
        ];
    }

    public static function updateRule()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'license_no' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'pin_code' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
