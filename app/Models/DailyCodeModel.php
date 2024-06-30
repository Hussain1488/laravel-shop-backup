<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCodeModel extends Model
{
    use HasFactory;

    protected $table = 'daily_code';

    protected $fillable = ['insta', 'rubika', 'site', 'eitaa', 'date'];

    public function lotteryCode()
    {
        return $this->belongsToMany(LotteryCodeModel::class, 'lottery_codes_daily_code', 'daily_code_id', 'lottery_code_id');
    }
}
