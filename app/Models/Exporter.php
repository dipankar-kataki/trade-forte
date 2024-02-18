<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    use HasFactory;

    protected $table = 'exporters';
    protected $hidden = ['created_at', 'updated_at',"status"];
    protected $guarded = [];
    // protected $casts = [
    //     "addresses" => 'array'
    // ];
    public static function createRule()
    {
        return [
            'name' => 'required|string|max:255',
            'pincode' => 'required|integer|max:9999999999',
            'gst_no' => 'required|string|max:30',
            'iec_no' => 'required|string|max:30',
            'logo' => 'nullable',
            'customer_category' => 'required|string',
            'organization_type' => 'required|string|in:PROPRIETORSHIP,PARTNERSHIP,PRIVATE,OPC,HUF,SOCIETY,TRUST',
            'lut_no' => 'required|string|max:30',
            'state' => 'required|string|max:255',
            'organization_reg_no' => 'required|string|max:30',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
            'authorised_signatory_sex' => 'required|string|in:MALE,FEMALE',
            'authorised_signatory_dob' => 'required|date',
            'authorised_signatory_pan' => 'required|string|max:30',
            'authorised_signatory_father' => 'required|string|max:30',
            'authorised_signatory_aadhar' => 'required|string|max:30',
            'organization_email' => 'required|email|max:255',
            'organization_phone' => 'required|string|max:15',
            'firm_pan_no' => 'required|string|max:30',
            'status' => 'boolean',
        ];
    }
    public static function updateRule()
    {
        return [
            "exporter_id" => "required|exists:exporters,id",
            'name' => 'required|string|max:255',
            'pincode' => 'required|integer|max:9999999999',
            'gst_no' => 'required|string|max:30',
            'iec_no' => 'required|string|max:30',
            'logo' => 'nullable',
            'customer_category' => 'required|string',
            'organization_type' => 'required|string|in:PROPRIETORSHIP,PARTNERSHIP,PRIVATE,OPC,HUF,SOCIETY,TRUST',
            'lut_no' => 'required|string|max:30',
            'state' => 'required|string|max:255',
            'organization_reg_no' => 'required|string|max:30',
            'authorised_signatory_name' => 'required|string|max:255',
            'authorised_signatory_designation' => 'required|string|max:255',
            'authorised_signatory_sex' => 'required|string|in:MALE,FEMALE',
            'authorised_signatory_dob' => 'required|date',
            'authorised_signatory_pan' => 'required|string|max:30',
            'authorised_signatory_father' => 'required|string|max:30',
            'authorised_signatory_aadhar' => 'required|string|max:30',
            'organization_email' => 'required|email|max:255',
            'organization_phone' => 'required|string|max:15',
            'firm_pan_no' => 'required|string|max:30',
            'status' => 'required|boolean',
        ];
    }



    public function account_created_by()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function invoices()
    {
        return $this->hasMany(InvoiceDetail::class, 'exporter_id', 'id')->latest();
    }
    public function shippingAddress()
    {
        return $this->hasMany(ShippingAddress::class, 'exporter_id', 'id')->latest();
    }
}
