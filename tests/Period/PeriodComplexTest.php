<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\DateInvalidArgument;
use Awd\ValueObject\Period;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

#[CoversClass(Period::class)]
final class PeriodComplexTest extends TestCase
{
    private Period $instance;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new Period(new \DateInterval('P2Y6M4DT6H8M25S'));
    }

    public function testMustInstantiateCorrectItemFromIntervalSpec(): void
    {
        $dateInterval = new \DateInterval('P2Y6M4DT6H8M25S');

        $instance = Period::fromIntervalSpec('P2Y6M4DT6H8M25S');

        assertEquals($dateInterval, $instance->toDateInterval());
    }

    public function testMustInstantiateNegativeItemFromIntervalSpec(): void
    {
        $dateInterval = new \DateInterval('P2Y6M4DT6H8M25S');
        $dateInterval->invert = 1;

        $instance = Period::fromIntervalSpec('P2Y6M4DT6H8M25S', true);

        assertEquals($dateInterval, $instance->toDateInterval());
    }

    public function testMustThrowAnExceptionWhenProvidedWrongSpecification(): void
    {
        $this->expectException(DateInvalidArgument::class);

        Period::fromIntervalSpec('foo');
    }

    public function testMustThrowAnExceptionWhenProvidedWrongSpecificationWithCorrectCode(): void
    {
        $this->expectExceptionCode(2);

        Period::fromIntervalSpec('foo');
    }

    public function testMustSerializeAndUnserializeCorrectly(): void
    {
        $serialized = serialize($this->instance);

        assertTrue($this->instance->isSame(unserialize($serialized)));
    }

    public function testMustReturnCorrectTextRepresentationWhenCastsToString(): void
    {
        $dateInterval = new \DateInterval('P2Y6M4DT6H8M25S');
        $instance = new Period($dateInterval);

        assertSame('P2Y6M4DT6H8M25S', (string) $instance);
        assertEquals($dateInterval, $instance->toDateInterval());
    }

    public function testMustReturnCorrectTextRepresentationWhenCastsToStringFrom(): void
    {
        $dateInterval = new \DateInterval('P0Y0M42DT0H0M0S');
        $instance = new Period($dateInterval);

        $dateInterval1 = $instance->toDateInterval();
        assertEquals($dateInterval, $dateInterval1);
    }

    #[DataProvider('complexPeriodDataProvider')]
    public function testMustCreateProperComplexPeriod(
        \DateInterval $dateInterval,
        string $periodPeriod,
        int $periodDuration,
    ): void {
        $instance = Period::fromDuration($periodDuration, $periodPeriod);

        assertEquals($dateInterval, $instance->toDateInterval());
    }

    public static function complexPeriodDataProvider(): array
    {
        return [
            [new \DateInterval('PT255S'), 'second', 255],
            [new \DateInterval('PT5M'), 'minute', 5],
            [new \DateInterval('PT24H'), 'hour', 24],
            [new \DateInterval('P3D'), 'day', 3],
            [new \DateInterval('P294D'), 'week', 42],
            [new \DateInterval('P1M'), 'month', 1],
            [new \DateInterval('P2Y'), 'year', 2],
            [new \DateInterval('P42D'), 'day', 42],
        ];
    }
}
