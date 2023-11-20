<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'details_created_by', 'id');
    }

    public function consignee(){
        return $this->belongsTo(Consignee::class, 'consignee_id', 'id');
    }
}
