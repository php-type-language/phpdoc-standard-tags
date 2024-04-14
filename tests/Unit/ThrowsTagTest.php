<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\PHPDoc\Standard\ThrowsTag;
use TypeLang\PHPDoc\Standard\ThrowsTagFactory;
use TypeLang\PHPDoc\Tag\InvalidTagInterface;

#[Group('unit'), Group('type-lang/phpdoc-standard-tags')]
class ThrowsTagTest extends TestCase
{
    protected static function createFactories(): iterable
    {
        yield 'throws' => new ThrowsTagFactory();
    }

    public function testTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @throws int
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ThrowsTag(
            name: 'throws',
            type: new NamedTypeNode('int'),
        ), $phpdoc[0]);
    }

    public function testTagWithDescription(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @throws int Example description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ThrowsTag(
            name: 'throws',
            type: new NamedTypeNode('int'),
            description: 'Example description',
        ), $phpdoc[0]);
    }

    public function testEmptyTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @throws
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);

        /** @var InvalidTagInterface $tag */
        $tag = $phpdoc[0];

        self::assertTagIsInvalid($tag);
        self::assertInvalidTagReasonContains($tag, 'contains an incorrect type');
    }

    public function testMultilineTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @throws
             * type
             * description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ThrowsTag(
            name: 'throws',
            type: new NamedTypeNode('type'),
            description: 'description',
        ), $phpdoc[0]);
    }
}
