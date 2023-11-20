<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    use HasFactory;

    protected $table = 'consignees';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
