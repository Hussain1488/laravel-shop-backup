<?php

namespace App\Models;

use CreateStoreDocumentTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModal extends Model
{
    use HasFactory;

    protected $table = 'employee';
    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(createstore::class, 'shop_id');
    }

    public function photo()
    {
        return $this->hasMany(ShopReviewPhotoModel::class, 'employee_id');
    }

    public function reviews()
    {
        return $this->hasMany(ShopServiceReviewModel::class, 'employee_id');
    }

    public function refreshRating()
    {
        $rating = $this->reviews()->accepted()->employee()->sum('rating') / ($this->reviews()->accepted()->employee()->count() ?: 1);

        $this->update([
            'rating'        => $rating,
            'reviews_count' => $this->reviews()->accepted()->employee()->count()
        ]);
    }

    public function scopeReviewCount()
    {
        return $this->reviews()->count();
    }
}
