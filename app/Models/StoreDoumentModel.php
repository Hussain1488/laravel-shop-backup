<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDoumentModel extends Model
{
    use HasFactory;

    protected $table = 'store_document';
    protected $fillable = ['transaction_id', 'store_id', 'documents', 'numberofdocuments', 'description', 'type'];

    public function transaction()
    {
        return $this->belongsTo(banktransaction::class, 'transaction_id');
    }
}
