<?php

use Phattarachai\ThaiIdCardValidation\ThaiIdCard;

it('passes a valid number', function (string|int $value) {
    expect((new ThaiIdCard)->validate($value))->toBeTrue();
})->with([
    'string' => '9791910872358',
    'another string' => '1005141246335',
    'integer' => 9791910872358,
    'check digit wraps 10 to 0' => '1000000000050',
    'written with dashes elsewhere' => '1737063686013',
]);

it('fails a number whose check digit is wrong', function () {
    expect((new ThaiIdCard)->validate('9791910872359'))->toBeFalse();
});

it('fails anything that is not exactly 13 digits', function (mixed $value) {
    expect((new ThaiIdCard)->validate($value))->toBeFalse();
})->with([
    'letters that cast to 0' => 'abcdefghijkl1',
    'a letter among digits' => '979191087235a',
    'dashes' => '1-7370-63686-01-3',
    'spaces' => '9 7919 10872 35 8',
    'trailing newline' => "9791910872358\n",
    'leading plus' => '+979191087235',
    'Thai digits' => '๙๗๙๑๙๑๐๘๗๒๓๕๘',
    '12 digits' => '979191087235',
    '14 digits' => '97919108723580',
    'empty string' => '',
    'null' => null,
    'negative integer' => -979191087235,
    'float' => 9791910872358.0,
    'bool' => true,
    'array' => [['9791910872358']],
    'object' => new stdClass,
]);
