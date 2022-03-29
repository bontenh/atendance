<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Performance;

class NullPerformChangeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($insert_date)
    {
        $this->insert_date=$insert_date;
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
        $perform_user=Performance::where('insert_date',$this->insert_date)->where('user_id',$value)->first();
        
        if(!isset($perform_user)){
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
        return '存在しない実績記録を変更しようとしています。';
    }
}
