<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDocumentModel extends Model
{
    use HasFactory;

    protected $table = 'account_document';
    protected $fillable = ['debtor_trans_id', 'creditor_trans_id', 'documents', 'numberofdocuments', 'description'];

    public function dobtorDocument()
    {
        return $this->belongsTo(banktransaction::class, 'debtor_trans_id');
    }
    public function creditorDocument()
    {
        return $this->belongsTo(banktransaction::class, 'creditor_trans_id');
    }
}
