<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CooperationSales extends Model
{
    use HasFactory;

    // defining relation with MakeInstallments model
    public function makeInstallmentsms()
    {
        return $this->belongsTo(Makeinstallmentsm::class, 'user_id');
    }
}
