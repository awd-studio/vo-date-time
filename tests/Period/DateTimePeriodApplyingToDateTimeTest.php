<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateTime;
use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;

use function Awd\Tests\Shared\assertDateTimeEquals;
use function PHPUnit\Framework\assertSame;

#[CoversClass(DateTimePeriod::class)]
final class DateTimePeriodApplyingToDateTimeTest extends DateTimePeriodTestCase
{
    public function testMustReturnTheExactSameDateInstanceInCaseThePeriodIsEmpty(): void
    {
        $instance = new DateTimePeriod();
        $dt = DateTime::fromString('2024-12-28 00:00:00');

        $resultDt = $instance->appliedTo($dt);

        assertSame($dt, $resultDt);
    }

    public function testMustReturnModifiedDateTime(): void
    {
        $instance = new DateTimePeriod(seconds: 42);
        $dt = DateTime::fromString('2024-12-28 00:00:00');

        $resultDt = $instance->appliedTo($dt);

        assertDateTimeEquals(DateTime::fromString('2024-12-28 00:00:42'), $resultDt);
    }
}
