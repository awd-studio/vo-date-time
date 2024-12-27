<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject\Period;

use Awd\ValueObject\Period;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\Awd\ValueObject\Period::class)]
final class PeriodFormattingTest extends TestCase
{
    public function testMustUseProvidedSeparatorForPeriodParts(): void
    {
        $instance = Period::fromIntervalSpec('P1M2DT32M');

        $this->assertSame('1 month + 2 days + 32 minutes', $instance->toHumanString(' + '));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('toHumanStringDataProvider')]
    public function testMustFormatCorrectly(string $intervalSpec, string $expectedValue, bool $isNegative): void
    {
        $instance = Period::fromIntervalSpec($intervalSpec, $isNegative);

        $this->assertSame($expectedValue, $instance->toHumanString());
    }

    public static function toHumanStringDataProvider(): array
    {
        return [
            ['PT1S', '1 second', false],
            ['PT2S', '2 seconds', false],
            ['PT1M', '1 minute', false],
            ['PT2M', '2 minutes', false],
            ['PT1H', '1 hour', false],
            ['PT2H', '2 hours', false],
            ['P1D', '1 day', false],
            ['P2D', '2 days', false],
            ['P1W', '1 week', false],
            ['P2W', '2 weeks', false],
            ['P1M', '1 month', false],
            ['P2M', '2 months', false],
            ['P1Y', '1 year', false],
            ['P2Y', '2 years', false],

            ['PT1S', '1 second ago', true],
            ['PT2S', '2 seconds ago', true],
            ['PT1M', '1 minute ago', true],
            ['PT2M', '2 minutes ago', true],
            ['PT1H', '1 hour ago', true],
            ['PT2H', '2 hours ago', true],
            ['P1D', '1 day ago', true],
            ['P2D', '2 days ago', true],
            ['P1W', '1 week ago', true],
            ['P2W', '2 weeks ago', true],
            ['P1M', '1 month ago', true],
            ['P2M', '2 months ago', true],
            ['P1Y', '1 year ago', true],
            ['P2Y', '2 years ago', true],

            ['P1M2DT32M', '1 month, 2 days, 32 minutes', false],
            ['P1M2DT32M', '1 month, 2 days, 32 minutes ago', true],

            ['PT3600M', '3600 minutes', false],
            ['PT3600M', '3600 minutes ago', true],

            ['P7D', '1 week', false],
            ['P7D', '1 week ago', true],
            ['P14D', '2 weeks', false],
            ['P14D', '2 weeks ago', true],
            ['P15D', '15 days', false],
            ['P15D', '15 days ago', true],
        ];
    }
}
