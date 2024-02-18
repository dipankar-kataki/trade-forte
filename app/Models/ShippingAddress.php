<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';
    protected $hidden = ['created_at', 'updated_at', "status"];
    protected $guarded = [];


    public static function createRule()
    {
        return [
            'exporter_id' => 'required|exists:exporters,id',
            'address_line_1' => 'required|string',
            'address_line_2' => 'required|string',
            'pin_code' => 'required|string',
            "city"=>"required|string",
            "district"=>"required|string",
            "state"=>"required|string" ,           
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
