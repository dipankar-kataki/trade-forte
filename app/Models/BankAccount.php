<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'account_created_by', 'id');
    }

    public function exporter(){
        return $this->belongsTo(Exporter::class, 'exporter_id', 'id' );
    }
}
