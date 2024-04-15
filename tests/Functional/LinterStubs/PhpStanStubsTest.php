<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Functional\LinterStubs;

use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('type-lang/phpdoc-standard-tags')]
class PhpStanStubsTest extends LinterStubsTestCase
{
    protected static function getStubsDirectory(): string
    {
        return __DIR__ . '/phpstan';
    }
}
