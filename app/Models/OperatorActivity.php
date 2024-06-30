<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperatorActivity extends Model
{
    protected $table = "operator_activities";
    protected $fillable = [
        'operator_id',
        'user_id',
        'workdescription'
    ];
    use HasFactory;



    const ACTIVITY = [
        'CREATE_STORE' => [
            'KEY' => 'create_store',
            'value' => 'عملیات ایجاد فروشگاه جدید انجام شده ',
        ],
        'EDIT_STORE' => [
            'KEY' => 'edit_store',
            'value' => 'عملیات اصلاح فروشگاه جدید انجام شده ',
        ],
        'CREATE_DOCUMNET_INCREASE' => [
            'KEY' => 'create_document_increase',
            'value' => 'افزایش موجودی و ایجاد سند کاربر',
        ],
        'CREATE_DOCUMNET_DECREASE' => [
            'KEY' => 'create_document_decrease',
            'value' => 'کاهش موجودی و ایجاد سند مالی  کاربر',
        ],
        'BUYER_CREDIT' => [
            'KEY' => 'buyer_credit_increase',
            'value' => 'عملیات اعتبار دهی به کاربر انجام شده ',
        ],
        'STORE_CREDIT' => [
            'KEY' => 'store_credit_increase',
            'value' => 'عملیات اعتبار دهی به فروشگاه انجام شده ',
        ],
        'STORE_CREDIT_DECREASE' => [
            'KEY' => 'store_credit_decrease',
            'value' => 'عملیات کاهش اعتبار فروشگاه انجام شده ',
        ],
        'CREATE_INTERNAL_ACCOUNT' => [
            'KEY' => 'create_document',
            'value' => 'عملیات ایجاد حساب داخلی انجام شده ',
        ],
        'PAY_REQUEST_PAYMENT' => [
            'KEY' => 'create_document',
            'value' => 'عملیات پرداخت درخواست تسویه انجام شده ',
        ],
        'GIVIN_ROLES' => [
            'KEY' => 'create_document',
            'value' => 'عملیات دادن مقام به کاربر انجام شده ',
        ],
        'STORE_DOCUMENTـICREASE' => [
            'KEY' => 'store_document_increase',
            'value' => 'افزایش موجودی و ایجاد سند مالی فروشگاه',
        ],
        'STORE_DOCUMENTـDECREASE' => [
            'KEY' => 'store_document_decrease',
            'value' => 'کاهش موجودی و ایجاد سند مالی فروشگاه',
        ],
        'BANK_DOCUMENT_TRANS' => [
            'KEY' => 'bank_create_document',
            'value' => 'ایجاد سند مالی برای حساب های داخلی'
        ]

    ];
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function createActivity($user, $activity)
    {
        $activityKey = self::ACTIVITY[$activity]['value'] ?? 'NoN';
        // $data = self::ACTIVITY[$activity]['data'] ?? 'NoN';

        $newActivity = self::create([
            'operator_id' => Auth::user()->id,
            'user_id' => $user,
            'workdescription' => $activityKey,
        ]);
        return $newActivity->id;
    }
}
