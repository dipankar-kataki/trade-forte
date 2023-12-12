<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    use HasFactory;

    protected $table = 'exporters';

    protected $guarded = [];
    public static function createRule()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'pincode' => 'required|integer|max:9999999999',
            'phone' => 'required|string|max:15',
            'gst_no' => 'nullable|string|max:30',
            'iec_no' => 'nullable|string|max:30',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'lut_no' => 'nullable|string|max:30',
            'ppc_lic_no' => 'nullable|string|max:30',
            'seed_lic_no' => 'nullable|string|max:30',
            'fertilizer_lic_no' => 'nullable|string|max:30',
        ];
    }
    public static function updateRule()
    {
        return [
            "exporterId" => "required|exists:exporters,id",
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:15',
            'gst_no' => 'sometimes|string|max:30',
            'iec_no' => 'sometimes|string|max:30',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240',
            'lut_no' => 'sometimes|string|max:30',
            'ppc_lic_no' => 'sometimes|string|max:30',
            'seed_lic_no' => 'sometimes|string|max:30',
            'fertilizer_lic_no' => 'sometimes|string|max:30',
            'status' => 'sometimes|boolean',
        ];
    }

    public function account_created_by(
    ) {
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
