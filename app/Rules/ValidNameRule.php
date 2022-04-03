<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

//名前のバリデーション
class ValidNameRule implements Rule
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

        //カナ文字と漢字に一致するなら、false
        if(preg_match('/[ァ-ヶー]+/u',$value) and preg_match('/[一-龠]+/u',$value)){
            return false;
            //カナ文字または漢字に一致するなら、true
        }else if(preg_match('/^[ァ-ヶー]+$/u',$value) or preg_match('/^[一-龠]+$/u',$value)){
            return true;
        }else{
            //どちらも一致しないならば、false
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
        return $this->error.'で受け入れられない文字があります。';
    }
}
