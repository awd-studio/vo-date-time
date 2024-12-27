<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\ValueObject\DateTime;
use Awd\ValueObject\Period;
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

        $aDay = new Period(new \DateInterval('P1D'));

        assertTrue($dtInADay->isEqual($dt->inA($aDay)));
    }

    public function testMustNotImpactOnInstanceAfterAdding(): void
    {
        $dt = new DateTime(new \DateTimeImmutable('2020-03-26T18:14:00'));
        $nextDay = $dt->inA(new Period(new \DateInterval('P1D')));

        assertNotSame($dt, $nextDay);
        assertTrue($dt->isEqual(new DateTime(new \DateTimeImmutable('2020-03-26T18:14:00'))));
    }
}
