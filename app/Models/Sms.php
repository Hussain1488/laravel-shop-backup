<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $guarded = ['id'];

    const TYPES = [
        'VERIFY_CODE' => [
            'key'    => 'verify-code',
            'string' => 'کد تأیید خانه اقساط',
            'method' => 'verifyCode',
            'bodyId' => 201310
        ],
        'USER_CREATED' => [
            'key'    => 'user-created',
            'string' => 'خوش آمدگویی کاربر',
            'method' => 'userCreated',
        ],
        'USER_CREATE' => [
            'key'    => 'user-create',
            'string' => 'کد تأیید ثبت نام کاربر جدید',
            'method' => 'userCreate',
            'bodyId' => 201309
        ],
        'RESEND_VERIFY_CODE' => [
            'key'    => 'resend-verify-code',
            'string' => 'کد مجدد تأیید خانه اقساط',
            'method' => 'resendVerifyCode',
            'bodyId' => 201302
        ],
        'INSTA_CODE' => [
            'key'    => 'insta-code',
            'string' => 'کد تأیید پرداخت قسط خانه اقساط',
            'method' => 'InstaCode',
            'bodyId' => 201306
        ],
        'PRE_PAY_CODE' => [
            'key'    => 'pre-pay-code',
            'string' => 'کد تأیید پیش پرداخت خانه اقساط',
            'method' => 'PrePayCode',
            'bodyId' => 201305
        ],
        'ORDER_PAID' => [
            'key'    => 'order-paid',
            'string' => 'اطلاع رسانی پرداخت سفارش به مدیر',
            'method' => 'orderPaid'
        ],
        'USER_ORDER_PAID' => [
            'key'    => 'user-order-paid',
            'string' => 'اطلاع رسانی پرداخت سفارش به کاربر',
            'method' => 'userOrderPaid'
        ],
        'WALLET_AMOUNT_DECREASED' => [
            'key'    => 'wallet-amount-decreased',
            'string' => 'اطلاع رسانی کاهش موجودی کیف پول',
            'method' => 'walletAmountDecreased'
        ],
        'WALLET_AMOUNT_INCREASED' => [
            'key'    => 'wallet-amount-decreased',
            'string' => 'اطلاع رسانی افزایش موجودی کیف پول',
            'method' => 'walletAmountIncreased'
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        foreach (self::TYPES as $type) {
            if ($this->type == $type['key']) {
                return $type['string'];
            }
        }
    }
}
