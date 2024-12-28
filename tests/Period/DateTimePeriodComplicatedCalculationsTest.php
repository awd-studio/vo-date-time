<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodComplicatedCalculationsTest extends DateTimePeriodTestCase
{
    public function testMustSetProperValuesComplicatedCalculationsWithBasicSet(): void
    {
        $instance = new DateTimePeriod(days: 3, hours: 8, minutes: 30);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(30, $instance->minutes);
        self::assertSame(8, $instance->hours);
        self::assertSame(3, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesComplicatedCalculationsWithOverloading(): void
    {
        $instance = new DateTimePeriod(seconds: 289800);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(30, $instance->minutes);
        self::assertSame(8, $instance->hours);
        self::assertSame(3, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesComplicatedCalculationsWithBasicSetWithNegativeValues(): void
    {
        $instance = new DateTimePeriod(days: -3, hours: -8, minutes: -30);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(-30, $instance->minutes);
        self::assertSame(-8, $instance->hours);
        self::assertSame(-3, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesComplicatedCalculationsWithOverloadingWithNegativeValues(): void
    {
        $instance = new DateTimePeriod(seconds: -289800);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(-30, $instance->minutes);
        self::assertSame(-8, $instance->hours);
        self::assertSame(-3, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);
    }

    public function testMustSetProperValuesComplicatedCalculationsWithComputableSet(): void
    {
        $instance = new DateTimePeriod(weeks: 2, days: -2);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(0, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(12, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(0, $instance->years);


        $instance = new DateTimePeriod(years: 4, days: 1, seconds: -42);

        self::assertSame(0, $instance->microseconds);
        self::assertSame(-42, $instance->seconds);
        self::assertSame(0, $instance->minutes);
        self::assertSame(0, $instance->hours);
        self::assertSame(1, $instance->days);
        self::assertSame(0, $instance->months);
        self::assertSame(4, $instance->years);
    }
}
