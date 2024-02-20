<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    use HasFactory;

    protected $table = 'consignees';
    protected $hidden = ['created_at', 'updated_at',"status"];
    protected $guarded = [];
    public static function createRule()
    {
        return [
            
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'foreign_business_country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'license_no' => 'required|string|max:255',
            'organization_phone' => 'required|string|max:255',
            'organization_email' => 'required|string|max:255',
            'pin_code' => 'required|string|max:255',
            'foreign_category' => 'required|string|in:importer,exporter',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
        ];
    }

    public static function updateRule()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'foreign_business_country' => 'required|string|max:255',
            'license_no' => 'required|string|max:255',
            'organization_phone' => 'required|string|max:255',
            'organization_email' => 'required|string|max:255',

            'pin_code' => 'required|string|max:255',
            'status' => 'nullable|boolean',
            'customer_category' => 'required|string|max:255',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
