<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reviewLike extends Model
{
    use HasFactory;

    protected $table = 'shop_likes';

    protected $guarded = ['id'];

    public function reviews()
    {
        return $this->belongsTo(ShopServiceReviewModel::class, 'reveiw_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
