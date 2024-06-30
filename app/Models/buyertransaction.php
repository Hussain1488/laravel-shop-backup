<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class buyertransaction extends Model
{
    use HasFactory;

    protected $table = 'buyertransactions';

    protected $guarded = [];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function bankTransaction()
    {
        return $this->hasOne(BankTransaction::class, 'buyer_trans_id');
    }

    public static function transaction($user, $amount, $status, $flag, $type, $description)
    {
        $user_transaction_number = buyertransaction::count();
        if ($user_transaction_number > 0) {
            $doc_number = buyertransaction::latest()->first()->documentnumber + 1;
            if (buyertransaction::where('flag', $flag)->where('user_id', $user->id)->count() > 0) {
                if ($status) {
                    $final_price = buyertransaction::where('flag', $flag)->where('user_id', $user->id)->latest()->first()->finalprice + $amount;
                } else {
                    $final_price = buyertransaction::where('flag', $flag)->where('user_id', $user->id)->latest()->first()->finalprice - $amount;
                }
            } else {
                if ($status) {

                    $final_price = +$amount;
                } else {

                    $final_price = -$amount;
                }
            }
        } else {
            $doc_number = 10000;
            if ($status) {

                $final_price = +$amount;
            } else {

                $final_price = -$amount;
            }
        }

        $user_trans = buyertransaction::create([
            'user_id' => $user->id,
            'flag' => $flag,
            'datetransaction' => Carbon::now(),
            'typeoftransaction' => $status ? '1' : '0',
            'price' => $amount,
            'finalprice' => $final_price,
            'documentnumber' => $doc_number,
            'description' => $description,
        ]);
        return $user_trans;
    }
}
