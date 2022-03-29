<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InputPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($admin_password)
    {
        $this->admin_password=$admin_password;
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
        if($value==$this->admin_password){
            return true;
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
        return '確認用のパスワードと合っていません。';
    }
}
