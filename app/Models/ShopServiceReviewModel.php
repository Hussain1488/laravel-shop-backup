<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopServiceReviewModel extends Model
{
    use HasFactory;

    protected $table = 'shop_service_review';
    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(ShopServiceModel::class, 'service_id');
    }
    public function employee()
    {
        return $this->belongsTo(EmployeeModal::class, 'employee_id');
    }
    public function shop()
    {
        return $this->belongsTo(createstore::class, 'shop_id');
    }

    public function point()
    {
        return $this->hasMany(ServiceReviewPointModel::class, 'review_id');
    }

    public function likes()
    {
        return $this->hasMany(reviewLike::class, 'reveiw_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeFilter($query)
    {
        $request = request();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        switch ($request->ordering) {
            case 'oldest': {
                    $query->oldest();
                    break;
                }
            default: {
                    $query->latest();
                }
        }

        return $query;
    }


    public function refreshLikesCount()
    {
        $this->update([
            'likes_count'    => $this->likes()->where('type', 'like')->count(),
            'dislikes_count' => $this->likes()->where('type', 'dislike')->count(),
        ]);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeShop($query)
    {
        return
            $query->where('type', 'shop');
    }
    public function scopeEmployee($query)
    {
        return $query->where('type', 'employee');
    }
    public function scopeService($query)
    {
        return
            $query->where('type', 'service');
    }

    public function scopeRefreshRating($query)
    {
        if ($query->type == 'shop') {
            $this->shop->refreshRating();
        } else if ($query->type == 'employee') {
            $this->employee->refreshRating();
        } else if ($query->type == 'service') {
            $this->service->refreshRating();
        } else return false;
    }
}
