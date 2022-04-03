<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NullRule;
use App\Rules\SameUserCompareRule;
use App\Rules\ValidKanaRule;
use App\Rules\ValidNameRule;

//利用者作成のバリデーション
class CreateUserRequest extends FormRequest
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
            'last_name'=>[new SameUserCompareRule($this->first_name), new NullRule('姓'), new ValidNameRule('姓')],
            'first_name'=>[new NullRule('名'), new ValidNameRule('名')],
            'last_name_kana'=>[new NullRule('姓のカナ'), new ValidKanaRule('姓のカナ')],
            'first_name_kana'=>[new NullRule('名のカナ'), new ValidKanaRule('名のカナ')],
        ];
    }
}
