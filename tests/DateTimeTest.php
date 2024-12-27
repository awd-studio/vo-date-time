<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\ValueObject\DateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;

#[CoversClass(DateTime::class)]
final class DateTimeTest extends TestCase
{
    public function testMustReturnSameNativeDateTime(): void
    {
        $native = new \DateTimeImmutable('04.01.2020 00:00:00');
        $instance = new DateTime($native);

        assertEquals($native, $instance->toDateTime());
    }

    public function testMustReturnTheNameOfAWeekDay(): void
    {
        $native = new \DateTimeImmutable('2020-03-28T00:00:00'); // It's Saturday
        $instance = new DateTime($native);

        assertSame('Saturday', $instance->weekDay());
    }
}
