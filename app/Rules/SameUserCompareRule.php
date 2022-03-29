<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Usertable;

class SameUserCompareRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($first_name)
    {
        $this->first_name=$first_name;
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
        $user=Usertable::where('last_name',$value)->where('first_name',$this->first_name)->first();
        if(isset($user)){
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
        return '同じ名前の人がいます。';
    }
}
