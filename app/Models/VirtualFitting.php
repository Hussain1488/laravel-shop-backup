<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualFitting extends Model
{
    use HasFactory;

    protected $table = 'virtual_fitting';

    protected $guarded = ['id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
