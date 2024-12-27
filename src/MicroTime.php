<?php

declare(strict_types=1);

namespace Awd\ValueObject;

final readonly class MicroTime
{
    public function __construct(public float $value) {}

    public function inSeconds(): int
    {
        return (int) $this->value;
    }
}
