<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankTypeModel extends Model
{
    use HasFactory;

    protected $table = 'type_of_account';
    protected $fillable = ['Name'];

    public function account()
    {
        return $this->hasMany(BankAccount::class, 'account_type_id');
    }
}
