<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\InputPasswordRule;
use App\Rules\NullRule;
use App\Rules\ValidPasswordRule;

//管理者登録情報のバリデーション
class RegisterAdminRequest extends FormRequest
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
    //管理者名とパスワードと確認用パスワードのバリデーション
    public function rules()
    {
        return [
            'admin_name'=>[new NullRule('名前')],
            'admin_password'=>[new NullRule('パスワード'),new ValidPasswordRule()],
            'check_password'=>[new NullRule('確認用のパスワード'),new InputPasswordRule($this->admin_password)],
        ];
    }
}
