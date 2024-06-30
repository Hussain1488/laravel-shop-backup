<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CornjobModel extends Model
{
    use HasFactory;

    protected $table = 'cornjob_log';

    protected $fillable = ['name', 'description'];
}
