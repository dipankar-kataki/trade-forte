<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HsnTable extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at',"status","users_id"];
    protected $table = "hsn_table";
}
