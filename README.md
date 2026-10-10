# Thai ID Card Validation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phattarachai/thai-id-card-validation.svg?style=flat-square)](https://packagist.org/packages/phattarachai/thai-id-card-validation)
[![Tests](https://img.shields.io/github/actions/workflow/status/phattarachai/thai-id-card-validation/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/phattarachai/thai-id-card-validation/actions/workflows/run-tests.yml?query=branch%3Amain)
[![Code Style](https://img.shields.io/github/actions/workflow/status/phattarachai/thai-id-card-validation/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/phattarachai/thai-id-card-validation/actions/workflows/fix-php-code-style-issues.yml?query=branch%3Amain)
[![PHP Version](https://img.shields.io/packagist/dependency-v/phattarachai/thai-id-card-validation/php?style=flat-square&label=php&logo=php&logoColor=white)](https://packagist.org/packages/phattarachai/thai-id-card-validation)
![Laravel Version](https://img.shields.io/badge/laravel-12%20%7C%2013-FF2D20?style=flat-square&logo=laravel&logoColor=white)
[![Total Downloads](https://img.shields.io/packagist/dt/phattarachai/thai-id-card-validation.svg?style=flat-square)](https://packagist.org/packages/phattarachai/thai-id-card-validation)

Validation rule สำหรับตรวจเลขบัตรประชาชนไทย 13 หลัก ใช้ได้ทั้งใน Laravel และ PHP ทั่วไป

[English](#english)

- [ติดตั้ง](#ติดตั้ง)
- [ใช้ใน Laravel](#ใช้ใน-laravel)
- [ข้อความเมื่อไม่ผ่าน](#ข้อความเมื่อไม่ผ่าน)
- [เลขที่มีขีดหรือเว้นวรรค](#เลขที่มีขีดหรือเว้นวรรค)
- [ใช้ใน PHP ทั่วไป](#ใช้ใน-php-ทั่วไป)
- [อัปเกรดจาก 1.x](#อัปเกรดจาก-1x)

## ติดตั้ง

```bash
composer require phattarachai/thai-id-card-validation
```

ต้องใช้ PHP 8.3 ขึ้นไป และ Laravel 12 หรือ 13

## ใช้ใน Laravel

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule;

$request->validate([
    'id_card' => ['bail', 'required', 'digits:13', new ThaiIdCardRule],
]);
```

แนะนำให้ใส่ `digits:13` คู่กันเสมอ ตัว rule เองไม่ผ่านค่าที่ไม่ใช่ตัวเลข 13 หลักอยู่แล้ว
แต่ `digits:13` จะบอกผู้ใช้ได้ตรงกว่าว่าพิมพ์ไม่ครบหรือเกิน ส่วน `bail` ทำให้ขึ้นแค่ข้อความเดียว

rule นี้ตรวจแค่หลักสุดท้าย (check digit) ว่าคำนวณตรงกับ 12 หลักแรก
ไม่ได้ตรวจว่ามีคนที่ถือเลขนี้อยู่จริง เลขที่สุ่มขึ้นมาแต่ check digit ถูกก็ผ่าน
ถ้าต้องการยืนยันตัวตนต้องตรวจกับแหล่งข้อมูลอื่น

ต้องการเลขไว้ทดสอบระบบ ใช้ [เครื่องมือสุ่มเลขบัตรประชาชน](https://phattarachai.dev/tools/thai-id-card-generator) ได้

## ข้อความเมื่อไม่ผ่าน

ค่าเริ่มต้นคือ `รหัสบัตรประชาชนไม่ถูกต้อง` เปลี่ยนได้สามทาง เรียงจากที่ rule เลือกใช้ก่อน

1. ส่งข้อความหรือ lang key เข้า constructor ใช้กับ field นั้น field เดียว

   ```php
   new ThaiIdCardRule(':attribute ไม่ใช่เลขบัตรประชาชนที่ถูกต้อง')
   new ThaiIdCardRule('validation.custom_id_card')
   ```

2. เพิ่ม key `thai_id_card` ใน `lang/{locale}/validation.php` ของแอป ใช้กับทุก field และแปลตาม locale ได้

   ```php
   // lang/th/validation.php
   'thai_id_card' => ':attribute ไม่ใช่เลขบัตรประชาชนที่ถูกต้อง',

   // lang/en/validation.php
   'thai_id_card' => 'The :attribute must be a valid Thai ID card number.',
   ```

3. ถ้าไม่มีทั้งสองอย่าง ใช้ข้อความภาษาไทยค่าเริ่มต้น

ทุกแบบใช้ `:attribute` ได้ และ `$messages` ของ validator ก็ยังใช้ได้ตามปกติ
โดยใช้ชื่อ class เป็น key เช่น `'id_card.'.ThaiIdCardRule::class => '...'`

## เลขที่มีขีดหรือเว้นวรรค

rule รับเฉพาะตัวเลข 13 หลักติดกัน เลขแบบ `1-7370-63686-01-3` หรือ `1 7370 63686 01 3` จะไม่ผ่าน
ถ้าฟอร์มยอมให้พิมพ์แบบนั้น ให้ตัดขีดและช่องว่างออกก่อน validate ค่าที่บันทึกลงฐานข้อมูลจะได้เป็นแบบเดียวกันด้วย

```php
// ใน FormRequest
protected function prepareForValidation(): void
{
    $this->merge([
        'id_card' => str_replace(['-', ' '], '', (string) $this->input('id_card')),
    ]);
}
```

## ใช้ใน PHP ทั่วไป

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCard;

(new ThaiIdCard)->validate('1737063686013'); // true
(new ThaiIdCard)->validate('1737063686012'); // false
(new ThaiIdCard)->validate('abcdefghijkl1'); // false
```

รับ string หรือ int ค่าอื่นทั้งหมด (null, array, object, float) คืน `false`

## อัปเกรดจาก 1.x

- ต้องใช้ PHP 8.3 และ Laravel 12 ขึ้นไป
- `ThaiIdCardRule` เปลี่ยนไปใช้ `ValidationRule` แล้ว การใช้ `new ThaiIdCardRule` ใน rules เหมือนเดิม
  แต่ถ้าเคยเรียก `->passes()` เองให้เปลี่ยนไปใช้ `ThaiIdCard::validate()` หรือ `Validator::make()`
- ถ้าเคย extend class เพื่อ override `message()` ยังทำได้ แต่ต้องประกาศ `: string` เพิ่ม
  หรือเลิก extend แล้วส่งข้อความเข้า constructor แทน
- ค่าที่มีตัวอักษรปน เช่น `abcdefghijkl1` เคยผ่านใน 1.0 ตอนนี้ไม่ผ่านแล้ว

## English

A validation rule for 13-digit Thai national ID card numbers, for Laravel and plain PHP.
Requires PHP 8.3+ and Laravel 12 or 13.

```bash
composer require phattarachai/thai-id-card-validation
```

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule;

$request->validate([
    'id_card' => ['bail', 'required', 'digits:13', new ThaiIdCardRule],
]);
```

- **Pair it with `digits:13`.** The rule already rejects anything that is not exactly 13 digits, but
  `digits:13` tells the user the length is wrong, and `bail` keeps it to one message.
- **It checks the check digit only.** A number passes when its last digit matches the first twelve.
  It does not prove the number was issued to anyone.
- **Normalise first.** Dashes and spaces (`1-7370-63686-01-3`) fail. Strip them in
  `prepareForValidation()` so the stored value is clean too (see the snippet above).

**Message.** The rule uses, in order: a message or lang key passed to the constructor
(`new ThaiIdCardRule(':attribute is invalid')`), the app's `validation.thai_id_card` lang key, then the
Thai default `รหัสบัตรประชาชนไม่ถูกต้อง`. `:attribute` is replaced in all of them.

**Outside Laravel**, `(new ThaiIdCard)->validate($value)` returns a bool. It accepts a string or an int;
anything else returns `false`.

**Upgrading from 1.x.** The rule now implements `ValidationRule`, so `passes()` is gone. A subclass that
overrides `message()` must declare `: string`. Input containing letters, which 1.0 could pass, now fails.

## ผู้พัฒนา

พัฒนาและดูแลโดย [บริษัท ภัทรชัย อาร์ทิซาน จำกัด](https://phattarachai.dev) (Phattarachai Artisan)
บริษัทที่ปรึกษาและพัฒนาเว็บ ที่เรียนรู้และแบ่งปันกับชุมชน Laravel
แพ็กเกจนี้แบ่งปันให้ชุมชนนำไปใช้และต่อยอดได้อย่างอิสระ

ดูแพ็กเกจอื่นของเราได้ที่ [phattarachai.dev/open-source](https://phattarachai.dev/open-source)
และติดต่อเราได้ที่ [phattarachai.dev](https://phattarachai.dev/#contact)

## License

The MIT License (MIT). See [LICENSE](LICENSE).
