<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'details_added_by', 'id');
    }
}
