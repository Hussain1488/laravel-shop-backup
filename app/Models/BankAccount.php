<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = "createbankaccounts";
    protected $fillable = ['bankname', 'accountnumber', 'accounttype', 'account_type_id', 'gateway_id'];

    public function account_type()
    {
        return $this->belongsTo(bankTypeModel::class, 'account_type_id');
    }
    public function paymentDetails()
    {
        return $this->hasMany(paymentdetails::class, 'bank_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }
}
