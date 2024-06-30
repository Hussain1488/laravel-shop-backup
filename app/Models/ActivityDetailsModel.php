<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDetailsModel extends Model
{
    use HasFactory;

    protected $table = 'activity_details';
    protected $fillable = ['activity_id', 'data'];

    protected $casts = [
        'data' => 'json',
    ];

    public static function createActivityDetail($activityId, $data)
    {
        return self::create([
            'activity_id' => $activityId,
            'data' => $data,
        ]);
    }
}
