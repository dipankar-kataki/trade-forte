<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';

    protected $guarded = [];


    public static function createRule()
    {
        return [
            'consignee_id' => 'required|exists:consignees,id',
            'name' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
            'pin_code' => 'required|string',
            'status' => 'boolean',
        ];
    }
    public function consignee()
    {
        return $this->belongsTo(Consignee::class, 'consignee_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
