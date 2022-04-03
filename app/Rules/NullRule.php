<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NullRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->error=$value;
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
        //入力が空か判断する
        if(!isset($value)){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error.'が入力されていません';
    }
}
