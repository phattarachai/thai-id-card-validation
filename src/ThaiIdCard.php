<?php

namespace Phattarachai\ThaiIdCardValidation;

class ThaiIdCard
{
    public function validate($value)
    {
        $value .= '';

        if (strlen($value) !== 13) {
            return false;
        }

        for ($sum = 0, $i = 0; $i < 12; $i++) {
            $sum += (int)($value[$i]) * (13 - $i);
        }

        return (11 - ($sum % 11)) % 10 === (((int)substr($value, 12, 1)));
    }
}
