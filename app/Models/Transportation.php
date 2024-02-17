<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $table = 'transportation';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
            'mode_of_transport' => 'required|string',
            'bl_awb_lr_no' => 'required|string',
            'bl_awb_lr_date' => 'required|date',
            'transporter_name' => 'required|string',
            'vehicle_vessel_flight_no' => 'required|string',
            'challan_number' => 'required|string',
            'challan_date' => 'required|date',
            'eway_bill_no' => 'required|string',
            'eway_bill_date' => 'required|date',
            'pre_carriage_by' => 'required|string',
            'place_of_pre_carriage' => 'required|string',
        ];
    }
    public function invoice()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_details_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
