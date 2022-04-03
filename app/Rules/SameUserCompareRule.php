<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Usertable;

//同じ名前の人がいるか、確認する
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
        //@var Usertable 名前をキーに利用者を取得する
        $user = Usertable::where('last_name',$value)->where('first_name',$this->first_name)->first();
        //利用者が見つからなければ
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
