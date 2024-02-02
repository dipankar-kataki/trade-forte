<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    use HasFactory;

    protected $table = 'exporters';

    protected $guarded = [];
    protected $casts = [
        "addresses" => 'array'
    ];
    public static function createRule()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'addresses' => 'required',
            'pincode' => 'required|integer|max:9999999999',
            'gst_no' => 'required|string|max:30',
            'iec_no' => 'required|string|max:30',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'customer_category' => 'required|string',
            'organization_type' => 'required|string|in:PROP,PARTNERSHIP,PRIVATE,OPC,HUF,SOCIETY,TRUST',
            'lut_no' => 'nullable|string|max:30',
            'state' => 'nullable|string|max:255',
            'organization_reg_no' => 'nullable|string|max:30',
            'authorised_signatory_name' => 'nullable|string|max:255',
            'authorised_signatory_designation' => 'nullable|string|max:255',
            'authorised_signatory_sex' => 'nullable|string|in:MALE,FEMALE',
            'authorised_signatory_dob' => 'nullable|date',
            'authorised_signatory_pan' => 'nullable|string|max:30',
            'authorised_signatory_aadhar' => 'nullable|string|max:30',
            'organization_email' => 'nullable|email|max:255',
            'organization_phone' => 'nullable|string|max:15',
            'firm_pan_no' => 'nullable|string|max:30',
            'status' => 'boolean',
        ];
    }
    public static function updateRule()
    {
        return [
            "exporterId" => "required|exists:exporters,id",
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'addresses' => 'sometimes|string|max:255',
            'pincode' => 'sometimes|integer|max:9999999999',
            'phone' => 'sometimes|string|max:15',
            'gst_no' => 'sometimes|string|max:30',
            'iec_no' => 'sometimes|string|max:30',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'customer_category' => 'sometimes|string',
            'organization_type' => 'sometimes|string|in:PROP,PARTNERSHIP,PRIVATE,OPC,HUF,SOCIETY,TRUST',
            'lut_no' => 'sometimes|string|max:30',
            'state' => 'sometimes|string|max:255',
            'organization_reg_no' => 'sometimes|string|max:30',
            'authorised_signatory_name' => 'sometimes|string|max:255',
            'authorised_signatory_designation' => 'sometimes|string|max:255',
            'authorised_signatory_sex' => 'sometimes|string|in:MALE,FEMALE',
            'authorised_signatory_dob' => 'sometimes|date',
            'authorised_signatory_pan' => 'sometimes|string|max:30',
            'authorised_signatory_aadhar' => 'sometimes|string|max:30',
            'organization_email' => 'sometimes|email|max:255',
            'organization_phone' => 'sometimes|string|max:15',
            'firm_pan_no' => 'sometimes|string|max:30',
            'status' => 'sometimes|boolean',
            'logo_height' => 'sometimes|string',
            'logo_width' => 'sometimes|string',
            'pan_no' => 'sometimes|string|max:30',
        ];
    }


    public function account_created_by(
    ) {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
