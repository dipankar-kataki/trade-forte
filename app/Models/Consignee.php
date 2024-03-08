<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    use HasFactory;

    protected $table = 'consignees';
    protected $hidden = ['created_at', 'updated_at', "status", "users_id"];
    protected $guarded = [];
    public static function createRule()
    {
        return [
            'name' => 'required|string|max:255',
            'address_line_one' => 'required|string|max:255',
            'address_line_two' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pin_code' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'foreign_business_country' => 'required|string|max:255',
            'license_no' => 'required|string|max:255',
            'organization_phone' => 'required|string|max:255',
            'organization_email' => 'required|string|max:255',
            'foreign_category' => 'required|string|in:importer,exporter',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
        ];
    }

    public static function updateRule()
    {
        return [
            'name' => 'required|string|max:255',
            'address_line_one' => 'required|string|max:255',
            'address_line_two' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pin_code' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'foreign_business_country' => 'required|string|max:255',
            'license_no' => 'required|string|max:255',
            'organization_phone' => 'required|string|max:255',
            'organization_email' => 'required|string|max:255',
            'status' => 'nullable|boolean',
            'foreign_category' => 'required|string|max:255',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function consigneeBank()
    {
        return $this->hasOne(ConsigneeBank::class, 'consignees_id', 'id');
    }
}
