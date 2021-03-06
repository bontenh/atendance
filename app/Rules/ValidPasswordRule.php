<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

//パスワードのバリデーション(利便性のため、簡易にしている)
class ValidPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        //四文字か判断する
        if(strlen($value)==4){
            //小文字か、数字以外を判断する
            if(preg_match('/^[0-9a-z]+$/',$value)){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'パスワードは四文字で数字か小文字しか入力できません。';
    }
}
