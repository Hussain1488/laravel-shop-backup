<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryWinnersModel extends Model
{
    use HasFactory;

    protected $table = 'lottery_winners';
    protected $fillable = ['user_id', 'lottery_code_id', 'type', 'description', 'lottery_date'];


    public function latteryCode()
    {
        return $this->belongsTo(LotteryCodeModel::class, 'lottery_code_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
