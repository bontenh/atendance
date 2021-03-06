<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

//退席時間と出席時間の比較
class CompareStartEndRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($end)
    {
        $this->end=$end;
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
        //退席時間より出席時間が遅ければ、false
        if(strtotime($value) > strtotime($this->end)){
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
        return '開始時間と終了時間の値が逆転しています。';
    }
}
