<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodCastingToStringSimpleTest extends DateTimePeriodTestCase
{
    public function testMustFormatToAnEmptyString(): void
    {
        $instance = new DateTimePeriod();

        self::assertSame('', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectMicroseconds(): void
    {
        $instance = new DateTimePeriod(microseconds: 4242);

        self::assertSame('+4242 microseconds', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectSeconds(): void
    {
        $instance = new DateTimePeriod(seconds: 42);

        self::assertSame('+42 seconds', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectMinutes(): void
    {
        $instance = new DateTimePeriod(minutes: 42);

        self::assertSame('+42 minutes', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectHours(): void
    {
        $instance = new DateTimePeriod(hours: 2);

        self::assertSame('+2 hours', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectDays(): void
    {
        $instance = new DateTimePeriod(days: 2);

        self::assertSame('+2 days', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectMonths(): void
    {
        $instance = new DateTimePeriod(months: 2);

        self::assertSame('+2 months', (string) $instance);
    }

    public function testMustFormatAStringWithCorrectYears(): void
    {
        $instance = new DateTimePeriod(years: 2);

        self::assertSame('+2 years', (string) $instance);
    }
}
