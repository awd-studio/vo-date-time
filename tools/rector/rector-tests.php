<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\PHPUnit\PHPUnit100\Rector\Class_\StaticDataProviderClassMethodRector;

return RectorConfig::configure()
    ->withCache(cacheDirectory: __DIR__ . '/../cache/rector')
    ->withPaths([
        __DIR__ . '/../../tests',
    ])
    ->withPhpSets(php83: true)
    ->withSets([
        PHPUnitSetList::PHPUNIT_110,
    ])
    ->withAttributesSets(phpunit: true)
    ->withRules([
        DeclareStrictTypesRector::class,
        StaticDataProviderClassMethodRector::class,
    ])
;
