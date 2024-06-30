<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class CreateColleagueIndexRequest extends FormRequest
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
            "user_id" => "required",
            "purchasecredit" => "required|numeric",
            "documents" => "required",
            "enddate" => "required|date",
        ];
    }

    public function attributes()
    {
        return [
            "user_id" => "کابر",
            "purchasecredit" => "اعتبار خرید",
            "inventory" => "موجودی نقدی",
            "documents" => "مدارک",
            "enddate" => "تاریخ پایان",
        ];
    }
}
