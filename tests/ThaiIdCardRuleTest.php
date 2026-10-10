<?php

use Illuminate\Support\Facades\Validator;
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule;

function validateIdCard(mixed $value, ?ThaiIdCardRule $rule = null): Illuminate\Validation\Validator
{
    return Validator::make(['id_card' => $value], ['id_card' => [$rule ?? new ThaiIdCardRule]]);
}

it('passes a valid number', function () {
    expect(validateIdCard('9791910872358')->passes())->toBeTrue()
        ->and(validateIdCard(9791910872358)->passes())->toBeTrue();
});

it('fails an invalid number with the Thai default message', function (mixed $value) {
    $validator = validateIdCard($value);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('id_card'))->toBe('รหัสบัตรประชาชนไม่ถูกต้อง');
})->with([
    'wrong check digit' => '9791910872359',
    'letters' => 'abcdefghijkl1',
    'dashes' => '9-7919-10872-35-8',
    'array' => [['9791910872358']],
]);

it('uses validation.thai_id_card from the app lang files', function () {
    app('translator')->addLines(['validation.thai_id_card' => 'The :attribute is not a valid Thai ID.'], 'en');

    expect(validateIdCard('9791910872359')->errors()->first('id_card'))
        ->toBe('The id card is not a valid Thai ID.');
});

it('uses the message passed to the constructor', function () {
    app('translator')->addLines(['validation.thai_id_card' => 'from lang'], 'en');

    expect(validateIdCard('9791910872359', new ThaiIdCardRule(':attribute ไม่ถูกต้อง'))->errors()->first('id_card'))
        ->toBe('id card ไม่ถูกต้อง');
});

it('translates a lang key passed to the constructor', function () {
    app('translator')->addLines(['validation.custom_id' => 'custom :attribute'], 'en');

    expect(validateIdCard('9791910872359', new ThaiIdCardRule('validation.custom_id'))->errors()->first('id_card'))
        ->toBe('custom id card');
});

it('lets a subclass override message()', function () {
    $rule = new class extends ThaiIdCardRule
    {
        public function message(): string
        {
            return 'subclass message';
        }
    };

    expect(validateIdCard('9791910872359', $rule)->errors()->first('id_card'))->toBe('subclass message');
});

it('takes a validator message keyed by the rule class', function () {
    $validator = Validator::make(
        ['id_card' => '9791910872359'],
        ['id_card' => [new ThaiIdCardRule]],
        ['id_card.'.ThaiIdCardRule::class => 'from messages'],
    );

    expect($validator->errors()->first('id_card'))->toBe('from messages');
});
