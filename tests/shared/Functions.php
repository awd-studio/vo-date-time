<?php

declare(strict_types=1);

namespace Awd\Tests\Shared;

use Awd\ValueObject\IDateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;

/**
 * Asserts that date-time equals to another one.
 *
 */
function assertDateTimeEquals(
    IDateTime $expectedIDateTime,
    IDateTime $actualIDateTime,
    string $message = '',
): void {
    Assert::assertThat($expectedIDateTime, new DateTimeEquals($actualIDateTime), $message);
}

/**
 * Asserts that date-time not equals to another one.
 *
 */
function assertDateTimeNotEquals(
    IDateTime $expectedIDateTime,
    IDateTime $actualIDateTime,
    string $message = '',
): void {
    Assert::assertThat($expectedIDateTime, new LogicalNot(new DateTimeEquals($actualIDateTime)), $message);
}

/**
 * Asserts that date-time contain the same day as the other one.
 *
 */
function assertDateTimeIsSameDay(
    IDateTime $expectedIDateTime,
    IDateTime $actualIDateTime,
    string $message = '',
): void {
    Assert::assertThat($expectedIDateTime, new DateTimeSameDay($actualIDateTime), $message);
}

/**
 * Asserts that date-time contain different day as the other one.
 *
 */
function assertDateTimeIsNotSameDay(
    IDateTime $expectedIDateTime,
    IDateTime $actualIDateTime,
    string $message = '',
): void {
    Assert::assertThat($expectedIDateTime, new LogicalNot(new DateTimeSameDay($actualIDateTime)), $message);
}
