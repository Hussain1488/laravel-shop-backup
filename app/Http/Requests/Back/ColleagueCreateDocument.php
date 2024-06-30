<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class ColleagueCreateDocument extends FormRequest
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
            "debtor_type" => "required|numeric",
            "debtor" => "required|numeric",
            "creditor_type" => "required|numeric",
            "creditor" => "required|numeric",
            "amount" => "required",
            "description" => "required|max:700",
            "documents" => "required",
        ];
    }
    public function attributes()
    {

        return [
            "debtor_type" => "ماهیت بدهکار",
            "debtor" => "بدهکار",
            "creditor_type" => "ماهیت بستانکار",
            "creditor" => "بستانکار",
            "amount" => "مبلغ سند مالی",
            "documents" => "اسناد",
            "description" => "توضیحات",
        ];
    }
}
