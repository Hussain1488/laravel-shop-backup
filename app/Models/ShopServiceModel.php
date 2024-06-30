<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopServiceModel extends Model
{
    use HasFactory;

    protected $table = 'shop_service';

    protected $guarded = ['id'];


    public function shop()
    {
        return $this->belongsTo(createstore::class, 'shop_id');
    }

    public function photo()
    {
        return $this->hasMany(ShopReviewPhotoModel::class, 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(ShopServiceReviewModel::class, 'service_id');
    }

    public function scopeRefreshRating()
    {
        $rating = $this->reviews()->accepted()->service()->sum('rating') / ($this->reviews()->accepted()->service()->count() ?: 1);

        $this->update([
            'rating'        => $rating,
            'reviews_count' => $this->reviews()->accepted()->service()->count()
        ]);
    }
    public function scopeReviewCount()
    {
        return $this->reviews()->count();
    }
}
