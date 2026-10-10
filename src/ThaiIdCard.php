<?php

namespace Phattarachai\ThaiIdCardValidation;

class ThaiIdCard
{
    /**
     * Check a Thai national ID number against its check digit.
     *
     * The value must be exactly 13 digits, as a string or a non-negative integer.
     * Dashes, spaces and any other characters fail; strip them before calling.
     */
    public function validate(mixed $value): bool
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || preg_match('/^\d{13}$/D', $value) !== 1) {
            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $value[$i] * (13 - $i);
        }

        return (11 - ($sum % 11)) % 10 === (int) $value[12];
    }
}
