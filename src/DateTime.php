<?php

declare(strict_types=1);

namespace Awd\ValueObject;

/**
 * An implementation of the date-time interface.
 * The main entry-point to work with dates and time in domain layer.
 */
final readonly class DateTime implements IDateTime
{
    /**
     * Creates an instance with certain date and time.
     */
    public function __construct(private \DateTimeImmutable $dateTime) {}

    /**
     * Returns an instance with the current (system) date.
     *
     * @throws DateInvalidArgument
     */
    public static function now(): IDateTime
    {
        return new self(self::buildDateTimeImmutable());
    }

    /**
     * Returns an instance with current date at midnight.
     *
     * @throws DateInvalidArgument
     */
    public static function today(): IDateTime
    {
        return self::now()->withTime(0, 0, 0, 0);
    }

    /**
     * Returns an instance with a tomorrow`s datetime.
     */
    public static function tomorrow(): IDateTime
    {
        return new self(new \DateTimeImmutable('tomorrow'));
    }

    /**
     * Returns an instance with a yesterday`s datetime.
     */
    public static function yesterday(): IDateTime
    {
        return new self(new \DateTimeImmutable('yesterday'));
    }

    /**
     * Creates an instance from native.
     */
    public static function fromNative(\DateTimeInterface $dateTime): self
    {
        if ($dateTime instanceof \DateTimeImmutable) {
            return new self($dateTime);
        }

        return new self(\DateTimeImmutable::createFromMutable($dateTime));
    }

    #[\Override]
    public static function fromString(string $date): IDateTime
    {
        return new self(self::buildDateTimeImmutable($date));
    }

    #[\Override]
    public static function fromTimestamp(int $timestamp): IDateTime
    {
        return new self(self::buildDateTimeImmutable()->setTimestamp($timestamp));
    }

    #[\Override]
    public function withChangedTimezone(\DateTimeZone $timeZone): IDateTime
    {
        return new self($this->dateTime->setTimezone($timeZone));
    }

    #[\Override]
    public function toTimestamp(): int
    {
        return $this->dateTime->getTimestamp();
    }

    #[\Override]
    public function copy(): IDateTime
    {
        return new self(clone $this->dateTime);
    }

    #[\Override]
    public function withTime(int $hours = 0, int $minutes = 0, int $seconds = 0, int $microseconds = 0): IDateTime
    {
        return new self($this->dateTime->setTime($hours, $minutes, $seconds, $microseconds));
    }

    #[\Override]
    public function prevDay(): IDateTime
    {
        return new self($this->dateTime->sub(new \DateInterval('P1D')));
    }

    #[\Override]
    public function nextDay(): IDateTime
    {
        return new self($this->dateTime->add(new \DateInterval('P1D')));
    }

    #[\Override]
    public function inA(Period $period): IDateTime
    {
        return new self($this->dateTime->add($period->toDateInterval()));
    }

    #[\Override]
    public function max(IDateTime $compareTo): IDateTime
    {
        return $this->isGreaterThanOrEquals($compareTo) ? $this : $compareTo;
    }

    #[\Override]
    public function min(IDateTime $compareTo): IDateTime
    {
        return $this->isLessThanOrEquals($compareTo) ? $this : $compareTo;
    }

    #[\Override]
    public function hasSameDay(IDateTime $dateTime): bool
    {
        return $dateTime->format('d.m.Y') === $this->dateTime->format('d.m.Y');
    }

    #[\Override]
    public function isEqualWithoutMicroseconds(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime()->format('Y-m-d H:i:s') === $this->dateTime->format('Y-m-d H:i:s');
    }

    #[\Override]
    public function isEqual(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime()->format(\DateTimeInterface::ATOM) === $this->dateTime->format(\DateTimeInterface::ATOM);
    }

    #[\Override]
    public function isGreaterThan(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime() < $this->dateTime;
    }

    #[\Override]
    public function isLessThan(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime() > $this->dateTime;
    }

    #[\Override]
    public function isGreaterThanOrEquals(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime() <= $this->dateTime;
    }

    #[\Override]
    public function isLessThanOrEquals(IDateTime $dateTime): bool
    {
        return $dateTime->toDateTime() >= $this->dateTime;
    }

    #[\Override]
    public function isBetween(IDateTime $from, IDateTime $to): bool
    {
        return $this->isGreaterThan($from) && $this->isLessThan($to);
    }

    #[\Override]
    public function isExpired(): bool
    {
        return $this->isLessThan(self::now());
    }

    #[\Override]
    public function toDateTime(): \DateTimeImmutable
    {
        return clone $this->dateTime;
    }

    #[\Override]
    public function format(string $format = self::DEFAULT_FORMAT): string
    {
        return $this->dateTime->format($format);
    }

    #[\Override]
    public function weekDay(): string
    {
        return $this->format('l');
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * @return array{0: string}
     */
    public function __serialize(): array
    {
        return [serialize($this->dateTime)];
    }

    /**
     * @param array{0: string} $data
     */
    public function __unserialize(array $data): void
    {
        $value = unserialize($data[0], ['allowed_classes' => [\DateTimeImmutable::class]]);
        \assert($value instanceof \DateTimeImmutable);
        $this->dateTime = $value;
    }

    /**
     * Builds a new DateTimeImmutable instance from a string data representation.
     *
     * @param string $date the date
     *
     * @throws DateInvalidArgument
     */
    private static function buildDateTimeImmutable(string $date = 'now'): \DateTimeImmutable
    {
        try {
            return new \DateTimeImmutable($date);
        } catch (\Throwable $e) {
            throw new DateInvalidArgument($e->getMessage());
        }
    }
}
