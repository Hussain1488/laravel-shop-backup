<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReviewPhotoModel extends Model
{
    use HasFactory;

    protected $table = 'shop_service_photo';

    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(createstore::class, 'shop_id');
    }
    public function service()
    {
        return $this->belongsTo(ShopServiceModel::class, 'service_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModal::class, 'employee_id');
    }
}
