<?php

namespace Phattarachai\ThaiIdCardValidation;

use Illuminate\Contracts\Validation\Rule;

class ThaiIdCardRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new ThaiIdCard)->validate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'รหัสบัตรประชาชนไม่ถูกต้อง';
    }
}
