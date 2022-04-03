<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NullRule;
use App\Rules\VerifyPasswordRule;

//管理者のログイン認証
class LoginAdminRequest extends FormRequest
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
        //管理者名とパスワードをバリデーション,認証
        return [
            'admin_name'=>[new NullRule('名前')],
            'admin_password'=>[new NullRule('パスワード'),new VerifyPasswordRule($this->admin_name)],
        ];
    }
}
