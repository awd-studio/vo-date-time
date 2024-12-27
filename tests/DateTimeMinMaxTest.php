<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\ValueObject\DateTime;
use Awd\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use function PHPUnit\Framework\assertSame;

#[CoversClass(DateTime::class)]
final class DateTimeMinMaxTest extends AppTestCase
{
    public function testMustReturnSelfWhenItIsLarger(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:24:40');
        $instance2 = DateTime::fromString('2021-06-16 22:00:00');

        assertSame($instance1, $instance1->max($instance2));
    }

    public function testMustReturnSelfWhenItIsSameOnMax(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:24:40');
        $instance2 = DateTime::fromString('2021-06-16 22:24:40');

        assertSame($instance1, $instance1->max($instance2));
    }

    public function testMustReturnAnotherWhenItIsErlier(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:00:00');
        $instance2 = DateTime::fromString('2021-06-16 22:24:40');

        assertSame($instance2, $instance1->max($instance2));
    }

    public function testMustReturnSelfWhenItIsLess(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:00:00');
        $instance2 = DateTime::fromString('2021-06-16 22:24:40');

        assertSame($instance1, $instance1->min($instance2));
    }

    public function testMustReturnSelfWhenItIsSameOnMin(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:24:40');
        $instance2 = DateTime::fromString('2021-06-16 22:24:40');

        assertSame($instance1, $instance1->min($instance2));
    }

    public function testMustReturnAnotherWhenItIsLarger(): void
    {
        $instance1 = DateTime::fromString('2021-06-16 22:24:40');
        $instance2 = DateTime::fromString('2021-06-16 22:00:00');

        assertSame($instance2, $instance1->min($instance2));
    }
}
