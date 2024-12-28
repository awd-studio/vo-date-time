<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodCalculatingNegativeTest extends DateTimePeriodTestCase
{
    public function testMustSetProperValuesForMicroseconds(): void
    {
        $instance = new DateTimePeriod(microseconds: -1000042);

        self::assertSame(-42, $instance->microseconds);
        self::assertSame(-1, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForSeconds(): void
    {
        $instance = new DateTimePeriod(seconds: -102);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(-42, $instance->seconds);
        self::assertSame(-1, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForMinutes(): void
    {
        $instance = new DateTimePeriod(minutes: -102);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(-42, $instance->minutes);
        self::assertSame(-1, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForHours(): void
    {
        $instance = new DateTimePeriod(hours: -26);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(-2, $instance->hours);
        self::assertSame(-1, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForMonths(): void
    {
        $instance = new DateTimePeriod(months: -14);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(-2, $instance->months);
        self::assertSame(-1, $instance->years);
    }
}
