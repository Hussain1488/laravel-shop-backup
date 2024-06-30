<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentListModel extends Model
{
    use HasFactory;

    protected $table = 'list_of_payment';
    protected $fillable = ['store_id', 'list_id', 'depositamount', 'shabanumber', 'factor', 'depositdate', 'status', 'final_price', 'trans_id'];

    public function transaction()
    {
        return $this->belongsTo(createstoretransaction::class, 'trans_id');
    }
    public function store()
    {
        return $this->belongsTo(createstore::class, 'store_id');
    }
    public function details()
    {
        return $this->hasOne(paymentdetails::class, 'list_of_payment_id');
    }
}
