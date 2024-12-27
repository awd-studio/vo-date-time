<?php

declare(strict_types=1);

namespace Awd\Tests\ValueObject;

use Awd\ValueObject\DateTime;
use Awd\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use function Awd\Tests\Shared\assertDateTimeEquals;

#[CoversClass(DateTime::class)]
final class DateTimeSerializationTest extends AppTestCase
{
    public function testMustSerializeAndUnserializeCorrectly(): void
    {
        $dateTime = DateTime::now();

        $serialized = serialize($dateTime);
        /** @var DateTime $unserialized */
        $unserialized = unserialize($serialized);

        assertDateTimeEquals($unserialized, $dateTime);
    }
}
