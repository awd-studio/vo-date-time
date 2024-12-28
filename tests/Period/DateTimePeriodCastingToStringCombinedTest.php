<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodCastingToStringCombinedTest extends DateTimePeriodTestCase
{
    public function testMustFormatToAStringWithACorrectOrder(): void
    {
        $instance = new DateTimePeriod(
            seconds: 42,
            minutes: 42,
            hours: 12,
            days: 2,
            months: 2,
            years: 2,
            microseconds: 2222,
        );

        self::assertSame('+2 years +2 months +2 days +12 hours +42 minutes +42 seconds +2222 microseconds', (string) $instance);
    }

    #[DataProvider('skippingCases')]
    public function testMustFormatToAStringWithSkippedOmittedParts(DateTimePeriod $instance, $expectedValue): void
    {
        self::assertSame($expectedValue, (string) $instance);
    }

    public static function skippingCases(): \Generator
    {
        yield from [
            [
                new DateTimePeriod(seconds: 42, minutes: 42, hours: 12, days: 2, months: 2, years: 2, microseconds: 2222),
                '+2 years +2 months +2 days +12 hours +42 minutes +42 seconds +2222 microseconds',
            ],
            [
                new DateTimePeriod(seconds: -42, minutes: 42, hours: -12, days: 2, months: -2, years: 2, microseconds: -2222),
                '+2 years -2 months +2 days -12 hours +42 minutes -42 seconds -2222 microseconds',
            ],
            [
                new DateTimePeriod(weeks: 4),
                '+28 days',
            ],
            [
                new DateTimePeriod(weeks: -4),
                '-28 days',
            ],
            [
                new DateTimePeriod(years: 2, microseconds: 2222),
                '+2 years +2222 microseconds',
            ],
            [
                new DateTimePeriod(minutes: 42, hours: 12, days: 2, microseconds: 2222),
                '+2 days +12 hours +42 minutes +2222 microseconds',
            ],
            [
                new DateTimePeriod(seconds: 42, minutes: 42, hours: 12),
                '+12 hours +42 minutes +42 seconds',
            ],
            [
                new DateTimePeriod(days: 2, months: 2, years: 2),
                '+2 years +2 months +2 days',
            ],
            [
                new DateTimePeriod(minutes: 42, hours: 12, days: 2, months: 2, years: 2, microseconds: 2222),
                '+2 years +2 months +2 days +12 hours +42 minutes +2222 microseconds',
            ],
            [
                new DateTimePeriod(seconds: 42, minutes: 42, hours: 12, days: 2, months: 2, years: 2),
                '+2 years +2 months +2 days +12 hours +42 minutes +42 seconds',
            ],
            [
                new DateTimePeriod(seconds: 42, minutes: 42, hours: 12, days: 2, years: 2, microseconds: 2222),
                '+2 years +2 days +12 hours +42 minutes +42 seconds +2222 microseconds',
            ],
            [
                new DateTimePeriod(seconds: 42, minutes: 42, hours: 12, days: 2, months: 2, microseconds: 2222),
                '+2 months +2 days +12 hours +42 minutes +42 seconds +2222 microseconds',
            ],
            [
                new DateTimePeriod(seconds: 42, hours: 12, days: 2, months: 2, years: 2, microseconds: 2222),
                '+2 years +2 months +2 days +12 hours +42 seconds +2222 microseconds',
            ],
        ];
    }
}
