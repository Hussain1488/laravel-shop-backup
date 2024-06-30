<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'                   => 'required',
            'photo'                     => 'required',
            'uploaddocument'            => 'required',
            'nameofstore'               => 'required|string',
            'addressofstore'            => 'required|string',
            'storecredit'               => 'required',
            'enddate'                   => 'required|date',
            'feepercentage'             => 'required|numeric|between:1,100',
            'settlementtime'            => 'required|min:1|max:255',
            'account_id'                => 'required',
            'no_profit_installment'     => 'required|in:yes,no',
            'no_profit_fee'             => 'integer|between:15,100',
        ];
    }
    public function attributes()
    {
        return [
            'user_id'                 => 'کاربر',
            'uploaddocument'          => 'فایل مستندات',
            'nameofstore'             => 'نام فروشگاه',
            'addressofstore'          => 'آدرس فروشگاه',
            'storecredit'             => 'اعتبار فروشگاه',
            'enddate'                 => 'تاریخ پایان قرارداد',
            'feepercentage'           => 'درصد کارمزد فروشگاه',
            'settlementtime'          => 'زمان تصفیه',
            'account_id'              => 'حساب درآمد',
            'no_profit_installment'   => 'فروش اقساطی بدون کارمزد',
            'no_profit_fee'           => 'درصد کارمز برای فروشگاه برای خرید اقساطی',
        ];
    }
}
