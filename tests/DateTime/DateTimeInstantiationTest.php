<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\DateTime;

use Awd\Tests\Shared\AppTestCase;
use Awd\ValueObject\DateInvalidArgument;
use Awd\ValueObject\DateTime;
use Awd\ValueObject\IDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

use function Awd\Tests\Shared\assertDateTimeEquals;
use function Awd\Tests\Shared\assertDateTimeIsNotSameDay;
use function Awd\Tests\Shared\assertDateTimeIsSameDay;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;

#[CoversClass(DateTime::class)]
final class DateTimeInstantiationTest extends AppTestCase
{
    public function testMustCreateCorrectInstanceFromBothAndImmutableAndSimpleNativeDateTime(): void
    {
        $immutable = DateTime::fromNative(new \DateTimeImmutable());
        $simple = DateTime::fromNative(new \DateTime());

        assertInstanceOf(DateTime::class, $immutable);
        assertInstanceOf(DateTime::class, $simple);
    }

    #[DataProvider('dateTimeFormattedStringDataProvider')]
    public function testMustCreateAnInstanceFromStringWithCertainDateAndTime(string $value): void
    {
        $native = new \DateTimeImmutable($value);
        $instance = DateTime::fromString($value);

        assertInstanceOf(DateTime::class, $instance);
        assertSame($native->format('d.m.Y H:i:s'), $instance->format('d.m.Y H:i:s'));
    }

    public function testMustThrowAnExceptionWhenTriesToSetInvalidStringToInstantiate(): void
    {
        $this->expectException(DateInvalidArgument::class);

        DateTime::fromString('not a date');
    }

    public function testMustCreateAnotherDateTimeInstanceWithSentTime(): void
    {
        $initial = new DateTime(new \DateTimeImmutable('2020-04-04 01:23:45'));

        $withTime = $initial->withTime(12, 42, 55, 69);

        assertInstanceOf(DateTime::class, $withTime);
        assertDateTimeEquals($withTime, DateTime::fromString('2020-04-04 12:42:55.000069'));
        assertDateTimeEquals($initial, DateTime::fromString('2020-04-04 01:23:45.0'));
    }

    public function testMustCreateAnInstanceFromUnixTimestamp(): void
    {
        $timestamp = 1585963425; // This is a UNIX timestamp of 2020-04-04 01:23:45 UTC
        $instance = DateTime::fromTimestamp($timestamp);

        assertInstanceOf(DateTime::class, $instance);
        assertDateTimeEquals($instance, new DateTime((new \DateTimeImmutable())->setTimestamp($timestamp)));
    }

    public function testMustReturnANewInstanceWithAPreviousDayValue(): void
    {
        $initial = new DateTime(new \DateTimeImmutable('2020-04-04 01:23:45'));

        $prevDay = $initial->prevDay();

        assertInstanceOf(DateTime::class, $prevDay);
        assertDateTimeEquals($prevDay, DateTime::fromString('03.04.2020 01:23:45'));
        assertDateTimeEquals($initial, DateTime::fromString('2020-04-04 01:23:45'));
    }

    public function testMustReturnANewInstanceWithANextDayValue(): void
    {
        $initial = new DateTime(new \DateTimeImmutable('2020-04-04 01:23:45'));

        $nextDay = $initial->nextDay();

        assertInstanceOf(DateTime::class, $nextDay);
        assertDateTimeEquals($nextDay, DateTime::fromString('05.04.2020 01:23:45'));
        assertDateTimeEquals($initial, DateTime::fromString('2020-04-04 01:23:45'));
    }

    public function testMustReturnTrueIfAnotherInstanceHasTheSameDay(): void
    {
        $compDay = new DateTime(new \DateTimeImmutable('2020-04-04 00:00:00'));
        $sameDay = new DateTime(new \DateTimeImmutable('2020-04-04 12:25:54'));

        assertDateTimeIsSameDay($compDay, $sameDay);
    }

    public function testMustReturnFalseIfAnotherInstanceHasDifferentWeekDay(): void
    {
        $compDay = new DateTime(new \DateTimeImmutable('2020-04-04 00:00:00'));
        $lessDay = new DateTime(new \DateTimeImmutable('03.01.2020 23:59:59'));
        $mostDay = new DateTime(new \DateTimeImmutable('04.01.2020 00:00:00'));

        assertDateTimeIsNotSameDay($compDay, $lessDay);
        assertDateTimeIsNotSameDay($compDay, $mostDay);
    }

    public function testMustCreateANewInstanceWithCurrentTime(): void
    {
        assertInstanceOf(IDateTime::class, DateTime::now());
    }

    public function testMustCreateANewInstanceWithTomorrowDateTime(): void
    {
        assertDateTimeIsSameDay(DateTime::now()->nextDay(), DateTime::tomorrow());
    }

    public function testMustCreateANewInstanceWithYesterdayDateTime(): void
    {
        assertDateTimeIsSameDay(DateTime::now()->prevDay(), DateTime::yesterday());
    }

    public function testMustReturnANewInstanceWithTheSameDateTime(): void
    {
        $dateTime = DateTime::now();
        $copiedDateTime = $dateTime->copy();

        assertDateTimeEquals($dateTime, $copiedDateTime);
        assertNotSame($dateTime, $copiedDateTime);
    }

    /**
     * Returns a list of valid strings appropriate for the date-time instantiating.
     *
     */
    public static function dateTimeFormattedStringDataProvider(): array
    {
        return [['now'], ['2170-00-00T00:00:00'], ['04.01.2020 00:00:00'], ['04/01/2020']];
    }
}
