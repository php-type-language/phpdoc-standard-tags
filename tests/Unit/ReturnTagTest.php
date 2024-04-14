<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\PHPDoc\Standard\ReturnTag;
use TypeLang\PHPDoc\Standard\ReturnTagFactory;
use TypeLang\PHPDoc\Tag\InvalidTagInterface;

#[Group('unit'), Group('type-lang/phpdoc-standard-tags')]
class ReturnTagTest extends TestCase
{
    protected static function createFactories(): iterable
    {
        yield 'return' => new ReturnTagFactory();
    }

    public function testTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @return int
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ReturnTag(
            name: 'return',
            type: new NamedTypeNode('int'),
        ), $phpdoc[0]);
    }

    public function testTagWithDescription(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @return int Example description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ReturnTag(
            name: 'return',
            type: new NamedTypeNode('int'),
            description: 'Example description',
        ), $phpdoc[0]);
    }

    public function testEmptyTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @return
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
             * @return
             * type
             * description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new ReturnTag(
            name: 'return',
            type: new NamedTypeNode('type'),
            description: 'description',
        ), $phpdoc[0]);
    }
}
