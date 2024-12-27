<?php

declare(strict_types=1);

namespace Awd\Tests\Shared;

use Awd\ValueObject\DateTime;
use Awd\ValueObject\IDateTime;
use Awd\ValueObject\Period;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

abstract class AppTestCase extends TestCase
{
    use ProphecyTrait;

    /**
     * A basic date-time dummy instance, to insert everywhere it needs
     * just as a parameter, and supposes no behavior.
     *
     */
    protected static IDateTime $iDateTimeStub;

    /**
     * A basic period dummy instance, to insert everywhere it needs
     * just as a parameter, and supposes no behavior.
     *
     */
    protected static Period $periodStub;

    /**
     * Uses the PHPUnit API to fill up static values
     * before each test is executed.
     */
    #[\PHPUnit\Framework\Attributes\Before]
    protected function setUpDateTime(): void
    {
        static::$iDateTimeStub = $this->iDateTime();
        static::$periodStub = $this->period();
    }

    /**
     * Returns a prophecy for IDateTime.
     *
     */
    protected function iDateTimeProphecy(): IDateTime|ObjectProphecy
    {
        return $this->prophesize(IDateTime::class);
    }

    /**
     * Returns a prophecy for Period.
     *
     */
    protected function periodProphecy(): ObjectProphecy|Period
    {
        return $this->prophesize(Period::class);
    }

    /**
     * Returns an instance of IDateTime.
     *
     */
    protected function iDateTime(): IDateTime
    {
        return $this->iDateTimeProphecy()->reveal();
    }

    /**
     * Returns an instance of IDateTime.
     *
     *
     */
    protected function generateIDateTime(?\DateTimeInterface $dateTime = null): IDateTime
    {
        if (null !== $dateTime) {
            return DateTime::fromNative($dateTime);
        }

        return DateTime::now();
    }

    /**
     * Returns an instance of Period.
     *
     */
    protected function period(): Period
    {
        return $this->periodProphecy()->reveal();
    }
}
