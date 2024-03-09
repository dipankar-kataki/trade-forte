<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingDetail extends Model
{
    use HasFactory;

    protected $table = 'packaging_details';

    protected $guarded = [];
    protected $hidden = ['updated_at', "status", "users_id"];
    public function user()
    {
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
    public static function createRule()
    {
        return [
            'invoice_details_id' => 'required|exists:invoice_details,id',
        ];
    }
    public static function updateRule()
    {
        return [
            'invoice_details_id' => 'sometimes|exists:invoice_details,id'
        ];
    }


    public function invoice()
    {
        return $this->belongsTo(InvoiceDetail::class, 'invoice_details_id', "id");
    }
    public function packaging_items()
    {
        return $this->hasMany(PackagingItems::class, 'packaging_id', "id");
    }
}
