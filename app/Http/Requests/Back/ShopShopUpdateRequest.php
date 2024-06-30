<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class ShopShopUpdateRequest extends FormRequest
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
            'nameofstore'               =>      'required|string',
            'addressofstore'            =>      'required|string',
            'feepercentage'             =>      'required|numeric',
            'settlementtime'            =>      'required|min:1|max:255',
            'no_profit_installment'     =>      'required|in:yes,no',
            'no_profit_fee'             =>      'integer|between:15,100',
        ];
    }
    public function attributes()
    {
        return [
            'user_id'                =>         'کاربر',
            'uploaddocument'         =>         'فایل مستندات',
            'nameofstore'            =>         'نام فروشگاه',
            'addressofstore'         =>         'آدرس فروشگاه',
            'storecredit'            =>         'اعتبار فروشگاه',
            'enddate'                =>         'تاریخ پایان قرارداد',
            'feepercentage'          =>         'درصد کارمزد فروشگاه',
            'settlementtime'         =>         'زمان تصفیه',
            'no_profit_installment'   => 'فروش اقساطی بدون کارمزد',
            'no_profit_fee'           => 'درصد کارمز برای فروشگاه برای خرید اقساطی',
        ];
    }
}
