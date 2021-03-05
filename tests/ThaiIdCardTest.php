<?php


use Phattarachai\ThaiIdCardValidation\ThaiIdCard;
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule;

class ThaiIdCardTest extends Orchestra\Testbench\TestCase
{

    /** @test */
    public function validate_pass()
    {
        self::assertTrue((new ThaiIdCard)->validate('9791910872358'));
        self::assertTrue((new ThaiIdCard)->validate('1005141246335'));
    }

    /** @test */
    public function validate_fail()
    {
        self::assertFalse((new ThaiIdCard)->validate('9791910872359'));
    }

    /** @test */
    public function rule_pass()
    {
        self::assertTrue((new ThaiIdCardRule)->passes('id_card', '9791910872358'));
        self::assertTrue((new ThaiIdCardRule)->passes('id_card', 9791910872358));
        self::assertTrue((new ThaiIdCardRule)->passes('id_card', '1005141246335'));
    }

    /** @test */
    public function rule_fail()
    {
        self::assertFalse((new ThaiIdCardRule)->passes('id_card', '9791910872359'));
    }

}
