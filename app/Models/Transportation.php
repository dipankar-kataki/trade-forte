<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $table = 'transportation';

    protected $guarded = [];


    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
            'details_created_by' => 'exists:users,id',
            'mode_of_transport' => 'required|string',
            'bl_awb_lr_no' => 'required|string',
            'bl_awb_lr_date' => 'required|string',
            'transporter_name' => 'required|string',
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
