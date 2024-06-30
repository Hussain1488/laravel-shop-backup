<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class Makeinstallmentsm extends Model
{
    use HasFactory;
    protected $table = 'makeinstallmentsms';
    protected $guarded = [];

    // defining relation with user model as user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // defining relation with user model as seller

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // defining relation with createstore model

    public function store()
    {
        return $this->belongsTo(createstore::class, 'store_id');
    }

    // defining relation with installmentsdetails model

    public function installments()
    {
        return $this->hasMany(installmentdetails::class, 'installment_id');
    }

    public function refuse($id)
    {
        $refuse = Makeinstallmentsm::find($id);
        $store = createstore::find($refuse->store_id);
        $store->storecredit += $refuse->Creditamount;
        // dd($refuse->Creditamount, $user->purchasecredit);
        $description = 'انصراف از فروش';
        $trans_data = [
            'تراکنش:' => $description,
            'توسط:' => Auth::user()->username,
            'مقدار فروش' =>  number_format($refuse->Creditamount) . ' ریال',
            'تاریخ:' => Jalalian::now()->format('Y/m/d'),
            'زمان:' => Jalalian::now()->format('H:i:s'),
        ];
        $store_trans = createstoretransaction::storeTransaction($store, $refuse->Creditamount, true, 3, 0, null, null, $description);
        StoreTransactionDetailsModel::createDetail($store_trans, $trans_data);
        $store->save();
        $refuse->delete();

        return true;
    }
}
