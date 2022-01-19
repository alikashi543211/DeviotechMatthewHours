<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidArrayRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $msg;
    public function __construct($msg)
    {
        $this->msg=$msg;
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
        if(in_array(null, $value, true))
        {
            return false;
        }else
        {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
