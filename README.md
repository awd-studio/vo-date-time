# Date-time value-object
## Provides a useful wrapper for dates as an value-object implementation.

Instances are immutable.

### Requirements:
- php >= 8.3

### Installation:
```sh
composer install awd-studio/vo-date-time
```

### Usage:
```php
<?php

use Awd\ValueObject\DateTime;
use Awd\ValueObject\DateTimePeriod;

// Comparing dates:
$dateTime1 = DateTime::fromString('2024-12-28');
$dateTime2 = DateTime::fromString('2024-12-30');
$dateTime3 = DateTime::fromString('2024-12-01');

$dateTime1->isEqual($dateTime2); // false
$dateTime1->isGreaterThan($dateTime2); // false
$dateTime1->isLessThanOrEquals($dateTime2); // true
$dateTime1->isBetween($dateTime3, $dateTime2) // true

// Modifying
$nextDayDt = $dateTime1->nextDay(); // Returns new instance for next day.
$copyDt = $dateTime1->copy(); // Returns a copy with same date.
$modDt = $dateTime1->modified(new DateTimePeriod(days: 1)); // Tomorrow.
$modDt = $dateTime1->modified(new DateTimePeriod(minutes: -5)); // 5 mins ago.
$modDt = $dateTime1->modified(new DateTimePeriod(weeks: 2, days: -2)); // In 12 days.
```
