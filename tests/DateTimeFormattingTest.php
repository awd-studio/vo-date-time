<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\ValueObject\DateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

#[CoversClass(DateTime::class)]
final class DateTimeFormattingTest extends TestCase
{
    public function testMustReturnDateTimeStringRepresentationAccordedToSelectedFormat(): void
    {
        $initial = new DateTime(new \DateTimeImmutable('04.04.2020 01:23:45')); // It's Saturday

        assertSame('Sat, April 4, 2020, 1:23 am', $initial->format('D, F j, Y, g:i a'));
        assertSame('04-04-2020 01:23:45', $initial->format('d-m-Y H:i:s'));
        assertSame('Saturday', $initial->format('l'));
    }

    public function testMustReturnDateTimeStringRepresentationWithTheDefaultFormatWhenNoArgumentsPassed(): void
    {
        $initial = new DateTime(new \DateTimeImmutable('04.04.2020 01:23:45'));

        assertSame('04.04.2020 01:23:45', $initial->format());
    }

    public function testMustReturnAStringWithDefaultFormat(): void
    {
        $native = new \DateTimeImmutable();
        $expected = $native->format(DateTime::DEFAULT_FORMAT);
        $instance = new DateTime($native);

        assertSame($expected, (string) $instance);
    }
}
