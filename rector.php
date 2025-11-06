<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/inc/plugins/headlize.php',
    ])
    ->withSets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION,
        SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
    ])
    ->withSkip([
        RemoveUnusedVariableAssignRector::class,
        UnwrapFutureCompatibleIfPhpVersionRector::class,
    ])
    ->withPhpVersion(PhpVersion::PHP_74);
