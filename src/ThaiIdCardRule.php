<?php

namespace Phattarachai\ThaiIdCardValidation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Lang;

class ThaiIdCardRule implements ValidationRule
{
    /**
     * The message used when neither the constructor nor the app's lang files give one.
     */
    public const string DEFAULT_MESSAGE = 'รหัสบัตรประชาชนไม่ถูกต้อง';

    /**
     * The lang key the rule looks up in the app's validation.php.
     */
    public const string TRANSLATION_KEY = 'validation.thai_id_card';

    /**
     * @param  string|null  $message  a message or a lang key; overrides validation.thai_id_card
     */
    public function __construct(private readonly ?string $message = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ((new ThaiIdCard)->validate($value)) {
            return;
        }

        $fail($this->message())->translate();
    }

    /**
     * The constructor's message, else the app's validation.thai_id_card, else the Thai default.
     */
    public function message(): string
    {
        if ($this->message !== null) {
            return $this->message;
        }

        return Lang::has(self::TRANSLATION_KEY) ? self::TRANSLATION_KEY : self::DEFAULT_MESSAGE;
    }
}
