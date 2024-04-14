<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Unit;

use PHPUnit\Framework\Attributes\Group;
use TypeLang\PHPDoc\DocBlock;
use TypeLang\PHPDoc\Parser;
use TypeLang\PHPDoc\ParserInterface;
use TypeLang\PHPDoc\Standard\Tests\TestCase as BaseTestCase;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Factory\TagFactory;
use TypeLang\PHPDoc\Tag\InvalidTagInterface;
use TypeLang\PHPDoc\Tag\TagInterface;

#[Group('unit'), Group('type-lang/phpdoc-standard-tags')]
abstract class TestCase extends BaseTestCase
{
    /**
     * @return iterable<non-empty-string|non-empty-list<non-empty-string>, FactoryInterface>
     */
    abstract protected static function createFactories(): iterable;

    protected static function createParser(): ParserInterface
    {
        $factory = new TagFactory();

        foreach (static::createFactories() as $tags => $delegate) {
            $factory->register($tags, $delegate);
        }

        return new Parser($factory);
    }

    protected static function parse(string $phpdoc): DocBlock
    {
        $parser = static::createParser();

        return $parser->parse($phpdoc);
    }

    protected static function assertTagIsInvalid(TagInterface $tag, string $message = ''): void
    {
        self::assertInstanceOf(InvalidTagInterface::class, $tag, $message);
    }

    protected static function assertInvalidTagReasonContains(
        InvalidTagInterface $tag,
        string $reason,
        string $message = '',
    ): void {
        $actual = $tag->getReason();

        self::assertStringContainsString($reason, $actual->getMessage(), $message);
    }

    protected static function assertInvalidTagReasonInstanceOf(
        InvalidTagInterface $tag,
        string $class,
        string $message = '',
    ): void {
        self::assertInstanceOf($class, $tag->getReason(), $message);
    }
}
