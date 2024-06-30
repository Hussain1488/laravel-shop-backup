<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class banktransaction extends Model
{
    use HasFactory;

    protected $table = "banktransactions";

    protected $fillable = ['bank_id', 'bankbalance', 'transactionprice', 'transactionsdate', 'buyer_trans_id', 'store_trans_id', 'type'];

    public function bank()
    {
        return $this->belongsTo(BankAccount::class, 'bank_id');
    }

    public function storeTransaction()
    {
        return $this->belongsTo(createstoretransaction::class, 'store_trans_id');
    }

    public function buyerTransaction()
    {
        return $this->belongsTo(buyertransaction::class, 'buyer_trans_id');
    }

    public function userDocument()
    {
        return $this->hasOne(createdocument::class, 'transaction_id');
    }
    public function storeDocument()
    {
        return $this->hasOne(StoreDoumentModel::class, 'transaction_id');
    }
    public function dobtorDocument()
    {
        return $this->hasOne(AccountDocumentModel::class, 'debtor_trans_id');
    }
    public function creditorDocument()
    {
        return $this->hasOne(AccountDocumentModel::class, 'creditor_trans_id');
    }

    public static function transaction($bank_id, $creditAmount, $status, $trans_id, $user)
    {

        $recordCount = banktransaction::where('bank_id', $bank_id)->count();
        if ($recordCount > 0) {
            $lastRecord = banktransaction::where('bank_id', $bank_id)->latest()->first();
            $bank = banktransaction::create([
                'bank_id' => $bank_id,
                'bankbalance' => $status ? $lastRecord->bankbalance + $creditAmount : $lastRecord->bankbalance - $creditAmount,
                'transactionprice' => $creditAmount,
                'type' => $status ? 'deposit' : 'withdraw',
                'transactionsdate' => Carbon::now()->format('Y-m-d'),
                'buyer_trans_id' => $user == 'user' ? $trans_id : null,
                'store_trans_id' => $user == 'store' ? $trans_id : null
            ]);
        } else {
            $bank = banktransaction::create([
                'bank_id' => $bank_id,
                'bankbalance' => $status ? $creditAmount : -$creditAmount,
                'transactionprice' => $creditAmount,
                'type' => $status ? 'deposit' : 'withdraw',
                'transactionsdate' => Carbon::now()->format('Y-m-d'),
                'buyer_trans_id' => $user == 'user' ? $trans_id : null,
                'store_trans_id' => $user == 'store' ? $trans_id : null
            ]);
        }
        return $bank;
    }
}
