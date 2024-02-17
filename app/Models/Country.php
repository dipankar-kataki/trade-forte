<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];
    public static function createRule()
    {
        return ["name" => "required|string"];
    }
}
