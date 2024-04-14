<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\Parser\Node\Stmt\NamedTypeNode;
use TypeLang\PHPDoc\Standard\VarTag;
use TypeLang\PHPDoc\Standard\VarTagFactory;
use TypeLang\PHPDoc\Tag\InvalidTagInterface;

#[Group('unit'), Group('type-lang/phpdoc-standard-tags')]
class VarTagTest extends TestCase
{
    protected static function createFactories(): iterable
    {
        yield 'var' => new VarTagFactory();
    }

    public function testTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @var int
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new VarTag(
            name: 'var',
            type: new NamedTypeNode('int'),
        ), $phpdoc[0]);
    }

    public function testTagWithName(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @var int $name
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new VarTag(
            name: 'var',
            type: new NamedTypeNode('int'),
            varName: 'name',
        ), $phpdoc[0]);
    }

    public function testTagWithDescription(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @var int Example description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new VarTag(
            name: 'var',
            type: new NamedTypeNode('int'),
            description: 'Example description',
        ), $phpdoc[0]);
    }

    public function testTagWithNameAndDescription(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @var int $name Example description
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new VarTag(
            name: 'var',
            type: new NamedTypeNode('int'),
            varName: 'name',
            description: 'Example description',
        ), $phpdoc[0]);
    }

    public function testEmptyTag(): void
    {
        $phpdoc = self::parse(<<<'PHPDOC'
            /**
             * @var
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
             * @var
             * type
             * $name
             */
            PHPDOC);

        self::assertCount(1, $phpdoc);
        self::assertEquals(new VarTag(
            name: 'var',
            type: new NamedTypeNode('type'),
            varName: 'name',
        ), $phpdoc[0]);
    }
}
