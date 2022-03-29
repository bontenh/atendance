<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Performance;

class SamePerformCompareRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id=$user_id;
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
        $perform_user=Performance::where('insert_date',$value)->where('user_id',$this->user_id)->first();
        if(isset($perform_user)){
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
        return '同日の同じ利用者が存在する為に作成しなくても大丈夫です。';
    }
}
