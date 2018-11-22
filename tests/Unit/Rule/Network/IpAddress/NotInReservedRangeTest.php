<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\IpAddress;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\IpAddress\NotInReservedRange;
use HarmonyIO\Validation\Rule\Rule;

class NotInReservedRangeTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new NotInReservedRange());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NotInReservedRange())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new NotInReservedRange())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidIpv4Addresses
     */
    public function testValidateReturnsTrueWhenPassingAnIpv4AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertTrue((new NotInReservedRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideInvalidIpv4Addresses
     */
    public function testValidateReturnsFalseWhenPassingAnIpv4AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertFalse((new NotInReservedRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideValidIpv6Addresses
     */
    public function xtestValidateReturnsTrueWhenPassingAnIpv6AddressThatIsNotWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertTrue((new NotInReservedRange())->validate($ipAddress));
    }

    /**
     * @dataProvider provideInvalidIpv6Addresses
     */
    public function testValidateReturnsFalseWhenPassingAnIpv6AddressThatIsWithinThePrivateRange(string $ipAddress): void
    {
        $this->assertFalse((new NotInReservedRange())->validate($ipAddress));
    }

    /**
     * @return string[]
     */
    public function provideValidIpv4Addresses(): array
    {
        return [
            ['1.1.1.1'],
            ['100.63.255.255'],
            ['100.128.0.0'],
            ['126.255.255.255'],
            ['128.0.0.0'],
            ['169.253.255.255'],
            ['169.255.0.0'],
            ['198.51.99.255'],
            ['198.51.101.0'],
            ['203.0.112.255'],
            ['203.0.114.0'],
            ['223.255.255.255'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv4Addresses(): array
    {
        return [
            ['0.0.0.0'],
            ['0.255.255.255'],
            ['100.64.0.0'],
            ['100.127.255.255'],
            ['127.0.0.0'],
            ['127.255.255.255'],
            ['169.254.0.0'],
            ['169.254.255.255'],
            ['198.51.100.0'],
            ['198.51.100.255'],
            ['203.0.113.0'],
            ['203.0.113.255'],
            ['224.0.0.0'],
            ['239.255.255.255'],
            ['240.0.0.0'],
            ['255.255.255.254'],
            ['255.255.255.255'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIpv6Addresses(): array
    {
        return [
            ['::'],
            ['::2'],
            ['99::ffff:ffff:ffff:ffff'],
            ['101::'],
            ['2001:db7:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['2001:db9::'],
            ['fbff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fe00::'],
            ['fe79:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fecf::'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidIpv6Addresses(): array
    {
        return [
            ['::'],
            ['::1'],
            ['100::'],
            ['100::ffff:ffff:ffff:ffff'],
            ['2001:db8::'],
            ['2001:db8:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fc00::'],
            ['fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['fe80::'],
            ['febf:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
            ['ff00::'],
            ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
        ];
    }
}
