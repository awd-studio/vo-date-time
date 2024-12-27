<?php

declare(strict_types=1);

namespace Awd\Tests\Shared;

use Awd\ValueObject\IDateTime;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Util\Exporter;

final class DateTimeSameDay extends Constraint
{
    public function __construct(private readonly IDateTime $expectedIDateTime) {}

    /**
     *
     */
    #[\Override]
    protected function matches($other): bool
    {
        return $this->expectedIDateTime->hasSameDay($other);
    }

    /**
     *
     */
    #[\Override]
    protected function failureDescription($other): string
    {
        return sprintf('%s is %s', Exporter::export($other->format('Y-d-m')), $this->toString());
    }

    /**
     *
     */
    #[\Override]
    public function toString(): string
    {
        return Exporter::export($this->expectedIDateTime->format('Y-d-m'));
    }
}
