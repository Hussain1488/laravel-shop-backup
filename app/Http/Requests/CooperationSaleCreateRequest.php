<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CooperationSaleCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "user_id" => "required",
            "Creditamount" => "required",
            "typeofpayment" => "required",
        ];
    }

    public function attributes()
    {
        return [
            "user_id" => "کاربر",
            "Creditamount" => "قیمت اصلی",
            "typeofpayment" => "نقدی یا اقساط",
            "numberofinstallments" => "تعداد اقساط",
            "prepayment" => "پیش پرداخت",
            "amounteachinstallment" => "مقدار هر قسط",
        ];
    }
}
