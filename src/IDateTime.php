<?php

declare(strict_types=1);

namespace Awd\ValueObject;

/**
 * The facade to work with date and time in the domain.
 */
interface IDateTime extends \Stringable
{
    /** @var string the default format to cast to a string */
    public const string DEFAULT_FORMAT = 'd.m.Y H:i:s';
    public const string FULL_DATE = 'Y-m-d\TH:i:s.uP';
    public const string ISO_FORMAT = 'c';
    public const string DAY_READING_FORMAT = 'l, j F';
    public const string DATABASE_DATE_FORMAT = 'Y-m-d';
    public const string DATABASE_DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const string DATABASE_DATETIME_MICRO_TIME_FORMAT = 'Y-m-d H:i:s.u';

    /**
     * Creates an instance from the string value of date.
     *
     * @throws DateInvalidArgument
     *
     * @see \DateTimeImmutable::__construct()
     */
    public static function fromString(string $date): self;

    /**
     * Creates an instance from the UNIX timestamp.
     *
     * @throws DateInvalidArgument
     */
    public static function fromTimestamp(int $timestamp): self;

    /**
     * Returns the same date, with provided timezone.
     */
    public function withChangedTimezone(\DateTimeZone $timeZone): self;

    /**
     * Returns a UNIX timestamp.
     */
    public function toTimestamp(): int;

    /**
     * Returns a copy of a current instance.
     */
    public function copy(): self;

    /**
     * Returns a new instance with modified time according to sent parameters.
     */
    public function withTime(int $hours = 0, int $minutes = 0, int $seconds = 0, int $microseconds = 0): self;

    /**
     * Returns a new instance that equals the previous day with same time as
     * the current one.
     */
    public function prevDay(): self;

    /**
     * Returns a new instance that equals the next day with same time as
     * the current one.
     */
    public function nextDay(): self;

    /**
     * Returns a new instance for a date which will be in a specific period.
     */
    public function modified(DateTimePeriod $period): self;

    /**
     * Returns one of dates that is larger.
     */
    public function max(self $compareTo): self;

    /**
     * Returns one of dates that is earlier.
     */
    public function min(self $compareTo): self;

    /**
     * Checks if the date is between two other.
     */
    public function isBetween(self $from, self $to): bool;

    /**
     * Compares the instance to another one without microseconds.
     */
    public function isEqualWithoutMicroseconds(self $dateTime): bool;

    /**
     * Compares the instance to another one to check if they have same value.
     */
    public function isEqual(self $dateTime): bool;

    /**
     * Compares the instance to another one to check if it is the most.
     */
    public function isGreaterThan(self $dateTime): bool;

    /**
     * Compares the instance to another one to check if it is the less.
     */
    public function isLessThan(self $dateTime): bool;

    /**
     * Compares the instance to another one to check if it is the most or equals.
     */
    public function isGreaterThanOrEquals(self $dateTime): bool;

    /**
     * Compares the instance to another one to check if it is the less or equals.
     */
    public function isLessThanOrEquals(self $dateTime): bool;

    /**
     * Checks if the date is from the past.
     */
    public function isExpired(): bool;

    public function hasSameDay(self $dateTime): bool;

    /**
     * Returns a copy of an instance as a native DateTime.
     */
    public function toDateTime(): \DateTimeImmutable;

    /**
     * Formats the date time into a human-readable string.
     *
     * @see \DateTimeImmutable::format()
     */
    public function format(string $format = self::DEFAULT_FORMAT): string;

    /**
     * Returns the name of a week day.
     */
    public function weekDay(): string;
}
