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
            'foreign_business_country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'license_no' => 'nullable|string|max:255',
            'organization_phone' => 'nullable|string|max:255',
            'organization_email' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:255',
            'foreign_category' => 'required|string|in:importer,exporter',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
        ];
    }

    public static function updateRule()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'addresses' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'license_no' => 'sometimes|string|max:255',
            'organization_phone' => 'sometimes|string|max:255',
            'organization_email' => 'sometimes|string|max:255',

            'pin_code' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'customer_category' => 'sometimes|string|max:255',
            'authorised_signatory_name' => 'sometimes|string|max:255',
            'authorised_signatory_designation' => 'sometimes|string|max:255',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
