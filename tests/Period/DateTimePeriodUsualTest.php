<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodUsualTest extends DateTimePeriodTestCase
{
    public function testMustSetProperValuesForMicroseconds(): void
    {
        $instance = new DateTimePeriod(microseconds: 424242);

        self::assertSame(424242, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForSmallMicroseconds(): void
    {
        $instance = new DateTimePeriod(microseconds: 42);

        self::assertSame(42, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForSeconds(): void
    {
        $instance = new DateTimePeriod(seconds: 42);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(42, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForMinutes(): void
    {
        $instance = new DateTimePeriod(minutes: 42);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(42, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForHours(): void
    {
        $instance = new DateTimePeriod(hours: 2);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(2, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForWeeks(): void
    {
        $instance = new DateTimePeriod(weeks: 1);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(7, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForMonths(): void
    {
        $instance = new DateTimePeriod(months: 2);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(2, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesForYears(): void
    {
        $instance = new DateTimePeriod(years: 2);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(0, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(2, $instance->years);
    }
}
