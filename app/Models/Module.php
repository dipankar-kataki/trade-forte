<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model {
    use HasFactory;

    protected $table = 'modules';

    protected $guarded = [];
    public static function createRule() {
        return [
            'name' => 'required|string',
            'permission' => 'required|integer',
        ];
    }
    public static function updateRule() {
        return [
            'moduleId' => 'required|exists:modules,id',
            "name" => "sometimes|string",
            'permission' => 'sometimes|integer',
        ];
    }
}
