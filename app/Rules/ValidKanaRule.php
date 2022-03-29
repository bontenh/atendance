<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidKanaRule implements Rule
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
        mb_regex_encoding('UTF-8');
        
        if(preg_match('/^[ァ-ヶー]+$/u',$value)){
            return true;
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
        return $this->error.'で受け入れられない文字があります.';
    }
}
