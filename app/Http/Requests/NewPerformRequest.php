<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CompareStartEndRule;
use App\Rules\SamePerformCompareRule;

class NewPerformRequest extends FormRequest
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
            'start'=>[new CompareStartEndRule($this->end)],
            'insert_date'=>[new SamePerformCompareRule($this->user_id)],
        ];
    }
}
