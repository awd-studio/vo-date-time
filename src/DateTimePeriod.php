<?php

declare(strict_types=1);

namespace Awd\ValueObject;

final readonly class DateTimePeriod implements \Stringable
{
    public int $years;
    public int $months;
    public int $days;
    public int $hours;
    public int $minutes;
    public int $seconds;
    public int $microseconds;

    public function __construct(
        int $seconds = 0,
        int $minutes = 0,
        int $hours = 0,
        int $days = 0,
        int $weeks = 0,
        int $months = 0,
        int $years = 0,
        int $microseconds = 0,
    ) {
        $hasExtraSeconds = 1_000_000 <= abs($microseconds);
        $this->microseconds = $hasExtraSeconds ? $microseconds % 1_000_000 : $microseconds;

        $seconds += (int) ($microseconds >= 0 ? floor($microseconds / 1_000_000) : ceil($microseconds / 1_000_000));
        $hasExtraMinutes = 60 <= abs($seconds);
        $this->seconds = $hasExtraMinutes ? $seconds % 60 : $seconds;

        $minutes += (int) ($seconds >= 0 ? floor($seconds / 60) : ceil($seconds / 60));
        $hasExtraHours = 60 <= abs($minutes);
        $this->minutes = $hasExtraHours ? $minutes % 60 : $minutes;

        $hours += (int) ($minutes >= 0 ? floor($minutes / 60) : ceil($minutes / 60));
        $hasExtraDays = 24 <= abs($hours);
        $this->hours = $hasExtraDays ? $hours % 24 : $hours;

        $days += (int) ($hours >= 0 ? floor($hours / 24) : ceil($hours / 24));
        $days += $weeks * 7; // adds weeks as days
        $hasExtraMonths = 30 <= abs($hours);
        $this->days = $hasExtraMonths ? $days % 30 : $days;

        $months += (int) ($days >= 0 ? floor($days / 30) : ceil($days / 30));
        $hasExtraYears = 12 <= abs($months);
        $this->months = $hasExtraYears ? $months % 12 : $months;

        $years += (int) ($months >= 0 ? floor($months / 12) : ceil($months / 12));
        $this->years = $years;
    }

    public function appliedTo(IDateTime $dateTime): IDateTime
    {
        if (0 === array_sum((array) $this)) {
            return $dateTime;
        }

        $phpDt = $dateTime->toDateTime();
        $modifiedPhpDt = $phpDt->modify((string) $this);

        return new DateTime($modifiedPhpDt);
    }

    #[\Override]
    public function __toString(): string
    {
        $parts = [];
        foreach ((array) $this as $part => $value) {
            if (0 !== $value) {
                $parts[] = \sprintf('%s%d %s', $value > 0 ? '+' : '', $value, $part);
            }
        }

        return implode(' ', $parts);
    }
}
