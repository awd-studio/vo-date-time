<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\Tests\Shared\AppTestCase;
use Awd\ValueObject\DateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

#[CoversClass(DateTime::class)]
#[CoversFunction('isEqual')]
#[CoversFunction('isEqualWithoutMicroseconds')]
#[CoversFunction('isGreaterThan')]
#[CoversFunction('isLessThan')]
#[CoversFunction('isGreaterThanOrEquals')]
#[CoversFunction('isLessThanOrEquals')]
#[CoversFunction('isExpired')]
#[CoversFunction('isBetween')]
final class DateTimeDefinitionTest extends AppTestCase
{
    public function testMustReturnTrueIfAnotherInstanceHasSameValue(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertTrue($compTime->isEqual($sameTime));
    }

    public function testMustReturnFalseIfAnotherInstanceHasDifferentValue(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $lessTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));
        $mostTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:01'));

        assertFalse($compTime->isEqual($lessTime));
        assertFalse($compTime->isEqual($mostTime));
    }

    public function testMustReturnFalseIfAnotherInstanceHasCompletelyDifferentValue(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00.000000'));
        $diffTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59.000000'));

        assertFalse($compTime->isEqualWithoutMicroseconds($diffTime));
    }

    public function testMustReturnTrueIfAnotherInstanceHasSameValueWithOmittedMicroseconds(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00.000000'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00.420000'));

        assertTrue($compTime->isEqualWithoutMicroseconds($sameTime));
    }

    public function testMustReturnTrueIfAnotherDateIsLessThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $lessTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));

        assertTrue($compTime->isGreaterThan($lessTime));
    }

    public function testMustReturnFalseIfAnotherDateIsNotLessThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $mostTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:01'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertFalse($compTime->isGreaterThan($mostTime));
        assertFalse($compTime->isGreaterThan($sameTime));
    }

    public function testMustReturnTrueIfAnotherDateIsMostThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $mostTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:01'));

        assertTrue($compTime->isLessThan($mostTime));
    }

    public function testMustReturnFalseIfAnotherDateIsNotMostThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $lessTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertFalse($compTime->isLessThan($lessTime));
        assertFalse($compTime->isLessThan($sameTime));
    }

    public function testMustReturnTrueIfAnotherInstanceIsNotLessThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $lessTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertTrue($compTime->isGreaterThanOrEquals($lessTime));
        assertTrue($compTime->isGreaterThanOrEquals($sameTime));
    }

    public function testMustReturnFalseIfAnotherInstanceIsMostThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $mostTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:01'));

        assertFalse($compTime->isGreaterThanOrEquals($mostTime));
    }

    public function testMustReturnTrueIfAnotherInstanceIsNotMostThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $mostTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:01'));
        $sameTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertTrue($compTime->isLessThanOrEquals($mostTime));
        assertTrue($compTime->isLessThanOrEquals($sameTime));
    }

    public function testMustReturnFalseIfAnotherInstanceIsLessThanCurrentOne(): void
    {
        $compTime = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));
        $lessTime = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));

        assertFalse($compTime->isLessThanOrEquals($lessTime));
    }

    public function testMustReturnTrueIfCurrentTimeIsMuchThanInstancesOne(): void
    {
        $pastTime = new \DateTimeImmutable('1970-00-00T00:00:00');
        $expiredInstance = new DateTime($pastTime);

        assertTrue($expiredInstance->isExpired());
    }

    public function testMustReturnFalseIfCurrentTimeIsLessThanInstancesOne(): void
    {
        $futureTime = new \DateTimeImmutable('2170-00-00T00:00:00');
        $notExpiredInstance = new DateTime($futureTime);

        assertFalse($notExpiredInstance->isExpired());
    }


    #[DataProvider('notBetweenDataProvider')]
    public function testMustReturnFalseIfDateTimeIsNotBetweenTheOtherTwo(
        string $fromValue,
        string $instanceValue,
        string $toValue,
    ): void {
        $instance = DateTime::fromString($instanceValue);
        $from = DateTime::fromString($fromValue);
        $to = DateTime::fromString($toValue);

        assertFalse($instance->isBetween($from, $to));
    }

    public static function notBetweenDataProvider(): array
    {
        return [
            ['2020-05-27 00:00:00', '2020-06-27 00:00:00', '2020-06-27 00:00:00'],
            ['2020-05-27 00:00:00', '2020-06-27 00:00:00', '2020-05-27 00:00:00'],
            ['2020-06-27 00:00:00', '2020-06-27 00:00:00', '2020-05-27 00:00:00'],
            ['2020-06-27 00:00:00', '2020-06-27 00:00:00', '2020-06-27 00:00:00'],
        ];
    }


    #[DataProvider('betweenDataProvider')]
    public function testMustReturnTrueIfDateTimeIsBetweenTheOtherTwo(
        string $fromValue,
        string $instanceValue,
        string $toValue,
    ): void {
        $instance = DateTime::fromString($instanceValue);
        $from = DateTime::fromString($fromValue);
        $to = DateTime::fromString($toValue);

        assertTrue($instance->isBetween($from, $to));
    }

    public static function betweenDataProvider(): array
    {
        return [
            ['2020-04-27 00:00:00', '2020-06-27 00:00:00', '2020-07-27 00:00:00'],
            ['2020-05-27 00:00:00', '2020-06-27 00:00:00', '2020-07-27 00:00:00'],
            ['2020-05-27 00:00:00', '2020-06-27 00:00:00', '2020-08-27 00:00:00'],
        ];
    }
}
