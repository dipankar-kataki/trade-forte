<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasFactory;
    protected $table = 'password_resets';
    protected $hidden = ['created_at', 'updated_at',"status"];
    protected $fillable = [
        'token',
        'email',
        'expires_at'
    ];
}
