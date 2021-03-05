[comment]: <> (![test]&#40;https://github.com/phattarachai/thaidate/actions/workflows/php.yml/badge.svg&#41;)

[comment]: <> ([![Packagist]&#40;https://img.shields.io/packagist/dt/phattarachai/thaidate.svg&#41;]&#40;https://github.com/phattarachai/thaidate/releases&#41;)

[comment]: <> ([![Maintainability]&#40;https://api.codeclimate.com/v1/badges/866379571541812960f6/maintainability&#41;]&#40;https://codeclimate.com/github/phattarachai/thaidate/maintainability&#41;)

# <a id="introduction"></a> 🔒 Thai ID Card Laravel Validation

แพคเกจนี้เพิ่ม Validation Rule สำหรับตรวจสอบความถูกต้องรหัสบัตรประชาชน สามารถใช้ได้ทั้งในใน Laravel และ PHP Project.

# <a id="installation"></a> ติดตั้ง

```
composer require phattarachai/thai-id-card-validation
```

# <a id="usage"></a> การใช้งาน

เรียกใช้ class ThaiIdCardRule ใน field ที่ต้องการ validate

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule;

// ใน controller
$this->validate($request, [
    'email' => 'required',
    'id_card_no' => new ThaiIdCardRule,
    // ... 
]);

```

หากต้องการตัวอย่างเลขรหัสบัตรประชาชน
สามารถทำได้ที่ [เครื่องมือสุ่มรหัสบัตรประชาชน](https://phattarachai.dev/tools/thai-id-card-generator)

# <a id="usage"></a> Validation Message

ถ้า validate ไม่ผ่านจะแสดงข้อความ `รหัสบัตรประชาชนไม่ถูกต้อง` เป็นค่าเริ่มต้ม ถ้าต้องการแก้ไข message สามารถ override
class เพื่อแก้ message ได้

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCardRule as Rule;


class ThaiIdCardRule extends Rule
{
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

```

# การใช้งานใน PHP (นอก Laravel Project)

ถ้าต้องการตรวจสอบรหัสบัตรประชาชนใน PHP หรือ Framework อื่น ๆ ที่ไม่ใช่ Laravel สามารถทำได้ผ่าน class ThaiIdCard

```php
use Phattarachai\ThaiIdCardValidation\ThaiIdCard;

$result = (new ThaiIdCard)->validate('1085217077105');
// true

$result = (new ThaiIdCard)->validate('1234567890123');
// false

```

## สนับสนุน

<a href="https://store.line.me/stickershop/author/2080520/en" target="_blank">
    <img src="https://me.phattarachai.dev/wp-content/uploads/2021/03/Banner.png"
        alt="Sticker Line by phattarachai.dev" width="45%" />
</a>

[phattarachai.dev](https://phattarachai.dev) เขียน blog และ opensource library ที่เกี่ยวกับ Laravel และ PHP
ให้นักพัฒนาชาวไทยได้ใช้ สามารถสนับสนุนผมได้ผ่านทาง LINE Sticker
ที่ผมได้ทำขึ้นได้เลยครับที่ [LINE Store](https://store.line.me/stickershop/author/2080520/en).

## Credit

<a href="https://twitter.com/phatchai" target="_blank">ติดตามผมได้ที่ Twitter</a>

<a href="https://ko-fi.com/phattarachai#checkoutModal" target="_blank">Buy me a Coffee</a>

## License

The MIT License (MIT)
