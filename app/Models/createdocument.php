<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class createdocument extends Model
{
    use HasFactory;
    protected $table = 'createdocuments';

    protected $fillable = ['transaction_id', 'user_id', 'documents', 'numberofdocuments', 'description', 'type'];

    public function transaction()
    {
        return $this->belongsTo(banktransaction::class, 'transaction_id');
    }
}
