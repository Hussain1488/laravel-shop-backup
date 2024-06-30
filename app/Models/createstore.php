<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class createstore extends Model
{
    use HasFactory;
    protected $table = 'createstores';
    protected $guarded = [];


    //  defining relation with user model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasMany(EmployeeModal::class, 'shop_id');
    }

    public function service()
    {
        return $this->hasMany(ShopServiceModel::class, 'shop_id');
    }

    public function gallery()
    {
        return $this->hasMany(ShopReviewPhotoModel::class, 'shop_id');
    }

    public function reviews()
    {
        return $this->hasMany(ShopServiceReviewModel::class, 'shop_id');
    }

    public function refreshRating()
    {
        $rating = $this->reviews()->accepted()->shop()->sum('rating') / ($this->reviews()->accepted()->shop()->count() ?: 1);

        $this->update([
            'rating'        => $rating,
            'reviews_count' => $this->reviews()->accepted()->shop()->count()
        ]);
    }

    // public function scopeAccepted($query)
    // {
    //     return $query->where('status', 'accepted')->where('type', 'shop');
    // }
}
