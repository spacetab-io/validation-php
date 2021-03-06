<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Numeric;

use Generator;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Exception\InvalidNumericValue;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Range;
use HarmonyIO\ValidationTest\Unit\Rule\NumericTestCase;

class RangeTest extends NumericTestCase
{
    /**
     * RangeTest constructor.
     *
     * @param string|null $name
     * @param array<mixed> $data
     * @param mixed $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Range::class, 13, 16);
    }

    public function testConstructorThrowsOnNonNumericMinimumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`one`) must be a numeric value.');

        new Range('one', 2);
    }

    public function testConstructorThrowsOnNonNumericMaximumValue(): void
    {
        $this->expectException(InvalidNumericValue::class);
        $this->expectExceptionMessage('Value (`two`) must be a numeric value.');

        new Range(1, 'two');
    }

    public function testConstructorThrowsWhenMinimumValueIsGreaterThanMaximumValue(): void
    {
        $this->expectException(InvalidNumericalRange::class);
        $this->expectExceptionMessage('The minimum (`51`) can not be greater than the maximum (`50`).');

        new Range(51, 50);
    }

    public function testValidateFailsWhenPassingAnIntegerSmallerThanMinimum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(12);

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerBiggerThanMaximum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(17);

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringSmallerThanMinimum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('12');

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAnIntegerAsAStringBiggerThanMaximum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('17');

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatSmallerThanMinimum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(12.9);

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatBiggerThanMaximum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(16.1);

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringSmallerThanMinimum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('12.9');

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Minimum', $result->getFirstError()->getMessage());
        $this->assertSame('minimum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(13.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenPassingAFloatAsAStringBiggerThanMaximum(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('16.1');

        $this->assertFalse($result->isValid());
        $this->assertSame('Numeric.Maximum', $result->getFirstError()->getMessage());
        $this->assertSame('maximum', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(16.0, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenPassingAnIntegerWithInRange(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(15);

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAnIntegerAsStringWithInRange(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('15');

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatWithInRange(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate(15.5);

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAFloatAsStringWithInRange(): Generator
    {
        /** @var Result $result */
        $result = yield (new Range(13, 16))->validate('15.5');

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
