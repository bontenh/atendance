<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Admin;

//管理者のログイン認証
class VerifyPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($admin_name)
    {
        $this->admin_name=$admin_name;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //@var Admin 名前に一致する管理者の情報を取得する
        $admin=Admin::where('name',$this->admin_name)->first();
        
        //管理者が登録されているか、判断する
        if(isset($admin)){
            //パスワードがあっているか、判断する
            if(password_verify($value,$admin->password)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '登録した管理者がいないかまたは管理者のパスワードが違います。';
    }
}
