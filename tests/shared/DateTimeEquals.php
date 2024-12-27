<?php

declare(strict_types=1);

namespace Awd\Tests\Shared;

use Awd\ValueObject\IDateTime;
use PHPUnit\Framework\Constraint\Constraint;

final class DateTimeEquals extends Constraint
{
    public function __construct(private readonly IDateTime $expectedIDateTime) {}

    /**
     *
     */
    #[\Override]
    protected function matches($other): bool
    {
        return $this->expectedIDateTime->isEqual($other);
    }

    /**
     *
     */
    #[\Override]
    protected function failureDescription($other): string
    {
        return sprintf('%s equals %s', $this->exporter()->export((string) $other), $this->toString());
    }

    /**
     *
     */
    #[\Override]
    public function toString(): string
    {
        return $this->exporter()->export((string) $this->expectedIDateTime);
    }
}
