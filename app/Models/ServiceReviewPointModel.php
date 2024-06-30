<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReviewPointModel extends Model
{
    use HasFactory;
    protected $table = 'service_review_point';

    protected $guarded = ['id'];

    public function review()
    {
        return $this->belongsTo(ShopServiceReviewModel::class, 'review_id');
    }
}
