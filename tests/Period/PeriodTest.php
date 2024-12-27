<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateInvalidArgument;
use Awd\ValueObject\Period;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Awd\ValueObject\Period::class)]
final class PeriodTest extends TestCase
{
    public function testMustAcceptANativeDateIntervalWithinConstruction(): void
    {
        $this->assertNotNull(new Period(new \DateInterval('P1M')));
    }

    public function testMustCreateANegativeInstance(): void
    {
        $dateInterval = new \DateInterval('P1M');
        $dateInterval->invert = 1;

        $instance = Period::fromDuration(1, 'month', true);

        $this->assertEquals($dateInterval, $instance->toDateInterval());
    }

    public function testGetIntervalFromWrongPeriod(): void
    {
        $this->expectException(DateInvalidArgument::class);

        Period::fromDuration(1, 'unknown period');
    }

    public function testGetIntervalFromWrongPeriodWithCorrectCode(): void
    {
        $this->expectExceptionCode(1);

        Period::fromDuration(1, 'unknown period');
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('periodsDataProvider')]
    public function testGetInterval(string $period, \DateInterval $dateInterval): void
    {
        $instance = Period::fromDuration(1, $period);
        $this->assertEquals($dateInterval, $instance->toDateInterval());
    }

    public static function periodsDataProvider()
    {
        return [
            ['second', new \DateInterval('PT1S')],
            ['minute', new \DateInterval('PT1M')],
            ['hour', new \DateInterval('PT1H')],
            ['day', new \DateInterval('P1D')],
            ['week', new \DateInterval('P1W')],
            ['month', new \DateInterval('P1M')],
            ['year', new \DateInterval('P1Y')],
        ];
    }

    public function testMustReturnTrueIfTheInstanceIsNegative(): void
    {
        $instance = Period::fromIntervalSpec('P2Y6M4DT6H8M25S', true);

        $this->assertTrue($instance->isNegative());
    }

    public function testMustReturnFalseIfTheInstanceIsNegative(): void
    {
        $instance = Period::fromIntervalSpec('P2Y6M4DT6H8M25S', false);

        $this->assertFalse($instance->isNegative());
    }

    public function testMustReturnCorrectTextRepresentationWhenCastsToStringForCalendarMonthValue(): void
    {
        $instance = Period::fromIntervalSpec('CALENDARP0Y1M0DT0H0M0S');

        $this->assertSame('CALENDARP0Y1M0DT0H0M0S', (string) $instance);
        $this->assertTrue($instance->isCalendarMonth());
        $this->assertEquals(new \DateInterval('P0Y1M0DT0H0M0S'), $instance->toDateInterval());
    }
}
