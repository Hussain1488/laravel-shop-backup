<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryCodeModel extends Model
{
    use HasFactory;

    protected $table = 'lottery_codes';

    protected $fillable = ['user_id', 'code', 'daily_code', 'invoice_id', 'weekly_state', 'monthly_state', 'state'];

    public function dailyCode()
    {
        return $this->belongsToMany(DailyCodeModel::class, 'lottery_codes_daily_code', 'lottery_code_id', 'daily_code_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function winner()
    {
        return $this->hasOne(LotteryWinnersModel::class, 'lottery_code_id');
    }

    public function invoice()
    {
        return $this->belongsTo(InvoicesModel::class, 'invoice_id');
    }
}
