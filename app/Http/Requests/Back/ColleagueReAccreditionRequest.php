<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class ColleagueReAccreditionRequest extends FormRequest
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
            "storecredit" => "required",
            "select_store" => "required",
        ];
    }
    public function attributes()
    {
        return [
            "storecredit" => "مقدار اعتبار",
            "select_store" => "انتخاب فروشگاه",
        ];
    }
}
