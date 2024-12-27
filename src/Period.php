<?php

declare(strict_types=1);

namespace Awd\ValueObject;

final readonly class Period implements \Stringable
{
    private const string CALENDAR_MONTH = 'CALENDAR';

    public const string SECOND = 'second';
    public const string MINUTE = 'minute';
    public const string HOUR = 'hour';
    public const string DAY = 'day';
    public const string WEEK = 'week';
    public const string MONTH = 'month';
    public const string YEAR = 'year';

    private const array PARTS = [
        'y' => self::YEAR,
        'm' => self::MONTH,
        'd' => self::DAY,
        'h' => self::HOUR,
        'i' => self::MINUTE,
        's' => self::SECOND,
    ];

    private \DateInterval $interval;
    private bool $isCalendarMonth;

    public function __construct(\DateInterval $dateInterval, bool $isCalendarMonth = false)
    {
        \assert(
            !$isCalendarMonth || 0 !== $dateInterval->m,
            'Calendar month might be set only for month dateinterval',
        );

        $this->interval = $dateInterval;
        $this->isCalendarMonth = $isCalendarMonth;
    }

    public static function fromDuration(
        int $duration,
        string $period,
        bool $isNegative = false,
        bool $isCalendarMonth = false,
    ): self {
        $lowerPeriod = mb_strtolower($period);

        try {
            $dateInterval = \DateInterval::createFromDateString($duration . ' ' . $lowerPeriod);
            \assert(false !== $dateInterval);
        } catch (\Throwable) {
            throw new DateInvalidArgument(\sprintf('Unknown period type "%s".', $period), 1);
        }

        if (true === $isNegative) {
            $dateInterval->invert = 1;
        }

        return new self($dateInterval, $isCalendarMonth);
    }

    public static function fromIntervalSpec(string $intervalSpec, bool $isNegative = false): self
    {
        $isCalendarMonth = str_starts_with($intervalSpec, self::CALENDAR_MONTH);
        if ($isCalendarMonth) {
            $intervalSpec = substr($intervalSpec, \strlen(self::CALENDAR_MONTH));
        }

        try {
            $dateInterval = new \DateInterval($intervalSpec);
        } catch (\Throwable) {
            throw new DateInvalidArgument(\sprintf('Invalid interval specification: "%s".', $intervalSpec), 2);
        }

        if (true === $isNegative) {
            $dateInterval->invert = 1;
        }

        return new self($dateInterval, $isCalendarMonth);
    }

    public function isCalendarMonth(): bool
    {
        return $this->isCalendarMonth;
    }

    public function isNegative(): bool
    {
        return 1 === $this->interval->invert;
    }

    public function toDateInterval(): \DateInterval
    {
        return $this->interval;
    }

    public function toHumanString(string $glue = ', '): string
    {
        $parts = [];
        foreach (self::PARTS as $periodPart => $period) {
            /**
             * @var int $duration
             *
             * @phpstan-ignore property.dynamicName
             */
            $duration = $this->interval->{$periodPart};
            if (0 < $duration) {
                if (self::DAY === $period && 0 === $duration % 7) {
                    $duration /= 7;
                    $period = 'week';
                }
                $period .= 1 === $duration ? '' : 's';
                $parts[] = $duration . ' ' . $period;
            }
        }

        return implode($glue, $parts) . ($this->isNegative() ? ' ago' : '');
    }

    #[\Override]
    public function __toString(): string
    {
        /** @psalm-suppress ImpureMethodCall */
        $format = $this->interval->format('P%yY%mM%dDT%hH%iM%sS');

        return $this->isCalendarMonth ? self::CALENDAR_MONTH . $format : $format;
    }

    public function isSame(self $period): bool
    {
        return (string) $this === (string) $period;
    }

    /**
     * @return array{0:string}
     */
    public function __serialize(): array
    {
        return [(string) $this];
    }

    /**
     * @param array{0:string} $data
     */
    public function __unserialize(array $data): void
    {
        $serialized = $data[0];

        $this->interval = new \DateInterval($serialized);
        $this->isCalendarMonth = str_starts_with($serialized, self::CALENDAR_MONTH);
    }
}
