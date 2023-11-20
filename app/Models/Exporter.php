<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    use HasFactory;

    protected $table = 'exporters';

    protected $guarded = [];

    public function account_created_by(){
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }
}
