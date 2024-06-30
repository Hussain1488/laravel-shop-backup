<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTransactionDetailsModel extends Model
{
    use HasFactory;
    protected $table = 'store_transaction_details';
    protected $fillable = ['transaction_id', 'data'];

    protected $casts = [
        'data' => 'json',
    ];
    public function transaction()
    {
        return $this->belongsTo(createstoretransaction::class, 'transaction_id');
    }

    public static function createDetail($transaction_id, $data)
    {
        return self::create([
            'transaction_id' => $transaction_id,
            'data' => $data,
        ]);
    }
}
