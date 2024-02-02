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
            'addresses' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'pin_code' => 'nullable|string|max:255',
            'customer_category' => 'required|string|in:importer,exporter',

        ];
    }

    public static function updateRule()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'addresses' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'license_no' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'pin_code' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'category' => 'sometimes|string|max:255',

        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
