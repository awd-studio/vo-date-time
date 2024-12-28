<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\DateTime;

use Awd\ValueObject\DateTime;
use Awd\ValueObject\DateTimePeriod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertTrue;

#[CoversClass(DateTime::class)]
final class DateTimeCalculationTest extends TestCase
{
    public function testMustReturnCorrectInstanceAfterAdding(): void
    {
        $dt = new DateTime(new \DateTimeImmutable('2020-03-26T18:14:00'));
        $dtInADay = new DateTime(new \DateTimeImmutable('2020-03-27T18:14:00'));

        $aDay = new DateTimePeriod(days: 1);

        assertTrue($dtInADay->isEqual($dt->modified($aDay)));
    }

    public function testMustNotImpactOnInstanceAfterAdding(): void
    {
        $dt = new DateTime(new \DateTimeImmutable('2020-03-26T18:14:00'));
        $nextDay = $dt->modified(new DateTimePeriod(days: 1));

        assertNotSame($dt, $nextDay);
        assertTrue($dt->isEqual(new DateTime(new \DateTimeImmutable('2020-03-26T18:14:00'))));
    }
}
