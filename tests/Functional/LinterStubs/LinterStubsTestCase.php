<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard\Tests\Functional\LinterStubs;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Finder\Finder;
use TypeLang\Parser\Node\Name;
use TypeLang\Parser\Node\Stmt\CallableTypeNode;
use TypeLang\PHPDoc\Standard\Tests\Concern\InteractWithPHPDocParser;
use TypeLang\PHPDoc\Standard\Tests\Concern\InteractWithPHPDocTagsParser;
use TypeLang\PHPDoc\Standard\Tests\Functional\TestCase;
use TypeLang\PHPDoc\Standard;
use TypeLang\PHPDoc\Tag\InvalidTag;
use TypeLang\PHPDoc\Tag\TagInterface;

#[Group('functional'), Group('type-lang/phpdoc-standard-tags')]
abstract class LinterStubsTestCase extends TestCase
{
    use InteractWithPHPDocParser;
    use InteractWithPHPDocTagsParser;

    /**
     * @return non-empty-string
     */
    abstract protected static function getStubsDirectory(): string;

    /**
     * @return iterable<non-empty-string, non-empty-string>
     * @throws \Throwable
     */
    protected static function getVersions(): iterable
    {
        $files = (new Finder())
            ->in(static::getStubsDirectory())
            ->directories()
            ->depth(0);

        foreach ($files as $file) {
            yield $file->getFilename() => $file->getPathname();
        }
    }

    /**
     * @param non-empty-string $directory
     * @return iterable<non-empty-string, non-empty-string>
     * @throws \Throwable
     */
    protected static function getFilesFromDirectory(string $directory): iterable
    {
        $files = (new Finder())
            ->in($directory)
            ->files()
            ->name(['*.php', '*.stub', '*.phpstub', '*.stubphp']);

        foreach ($files as $file) {
            yield $file->getRelativePathname() => $file->getPathname();
        }
    }

    /**
     * @return iterable<non-empty-string, non-empty-string>
     * @throws \Throwable
     */
    protected static function getFiles(): iterable
    {
        foreach (self::getVersions() as $version => $directory) {
            foreach (self::getFilesFromDirectory($directory) as $relative => $pathname) {
                yield $version . '-' . \strtolower($relative) => $pathname;
            }
        }
    }

    /**
     * @return iterable<non-empty-string, TagInterface>
     * @throws ExpectationFailedException
     * @throws \LogicException
     * @throws \Throwable
     */
    protected static function getAllTags(): iterable
    {
        $context = \pathinfo(static::getStubsDirectory(), \PATHINFO_FILENAME);
        $directory = __DIR__ . '/cache/' . $context;

        if (!\is_dir($directory)) {
            \mkdir($directory, recursive: true);
        }

        foreach (self::getFiles() as $name => $pathname) {
            $cache = $directory . '/' . \str_replace(['/', '\\'], '-', $name) . '.cache';

            // Read from cache
            if (\is_file($cache)) {
                yield from \unserialize(\file_get_contents($cache));
                continue;
            }

            $result = [];
            $docblocks = self::getDocBlocksFromPhpFile($pathname);

            foreach ($docblocks as $info => $docblock) {
                foreach (self::getTagsFromDocBlock($docblock) as $tag) {
                    $result[$name . ' ' . $info] = [$tag];
                }
            }

            yield from $result;

            \file_put_contents($cache, \serialize($result));
        }
    }

    /**
     * @param non-empty-list<non-empty-string> $name
     * @return iterable<non-empty-string, TagInterface>
     * @throws \LogicException
     * @throws ExpectationFailedException
     * @throws \Throwable
     */
    protected static function getTagByName(array $names): iterable
    {
        foreach (self::getAllTags() as $i => [$tag]) {
            if (\in_array($tag->getName(), $names, true)) {
                yield $i => [$tag];
            }
        }
    }

    public static function returnTagDataProvider(): iterable
    {
        yield from self::getTagByName(self::getPrefixedTags('return'));
    }

    #[DataProvider('returnTagDataProvider')]
    public function testReturnStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        self::assertInstanceOf(Standard\ReturnTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    public static function paramTagDataProvider(): iterable
    {
        yield from self::getTagByName([
            ...self::getPrefixedTags('param'),
            ...self::getPrefixedTags('param-out'),
        ]);
    }

    #[DataProvider('paramTagDataProvider')]
    public function testParamStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        // PHPStan SplObjectStorage docblock bug?
        $this->skipInCaseOfReasonPhraseContains($tag, 'contains an incorrect variable name:'
            . ' \SplObjectStorage<*, *> $storage');

        // Psalm redis docblock bug (param tag does not provide variable name)
        $this->skipInCaseOfReasonPhraseContains($tag, 'contains an incorrect variable name:'
            . ' array<int|string, string>');
        // Known phpdoc bug
        $this->skipInCaseOfReasonPhraseContains($tag, 'contains an incorrect variable name:'
            . ' $1 $2');

        self::assertInstanceOf(Standard\ParamTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    public static function varTagDataProvider(): iterable
    {
        yield from self::getTagByName(self::getPrefixedTags('var'));
    }

    #[DataProvider('varTagDataProvider')]
    public function testVarStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        self::assertInstanceOf(Standard\VarTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    public static function propertyTagDataProvider(): iterable
    {
        yield from self::getTagByName([
            'property',
            'property-read',
            'property-write',
        ]);
    }

    #[DataProvider('propertyTagDataProvider')]
    public function testPropertyStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        self::assertInstanceOf(Standard\PropertyTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    public static function methodTagDataProvider(): iterable
    {
        // PhpStan and Psalm stubs does not provide this tags
        yield '[sample]' => [new Standard\MethodTag(
            name: 'sample',
            type: new CallableTypeNode(new Name('sample'))
        )];

        yield from self::getTagByName(['method']);
    }

    #[DataProvider('methodTagDataProvider')]
    public function testMethodStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        // TODO Known phpdoc parser issue for legacy method signature
        $this->skipInCaseOfReasonPhraseContains($tag, 'mixed eval($script, $args = array(), $numKeys = 0)');

        self::assertInstanceOf(Standard\MethodTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    public static function throwsTagDataProvider(): iterable
    {
        yield from self::getTagByName(['throws']);
    }

    #[DataProvider('throwsTagDataProvider')]
    public function testThrowsStatementsIsCorrectlyRecognized(TagInterface $tag): void
    {
        // TODO Known phpdoc parser issue for html-tagged descriptions
        $this->skipInCaseOfReasonPhraseContains($tag, 'SolrServerException <p>');
        $this->skipInCaseOfReasonPhraseContains($tag, 'ArithmeticError <p>');

        self::assertInstanceOf(Standard\ThrowsTag::class, $tag,
            message: self::getReasonPhrase($tag),
        );
    }

    protected static function getReasonPhrase(TagInterface $tag): string
    {
        if ($tag instanceof InvalidTag) {
            $reason = $tag->getReason();

            return $reason->getMessage() . ': ' . (string) $tag->getDescription();
        }

        return 'Failed to parse tag: ' . \print_r($tag, true);
    }

    protected static function skipInCaseOfReasonPhraseContains(TagInterface $tag, string $message): void
    {
        if (!$tag instanceof InvalidTag) {
            return;
        }

        $phrase = self::getReasonPhrase($tag);

        if (!\str_contains($phrase, $message)) {
            return;
        }

        self::markTestIncomplete('TODO Known phpdoc parser issue: ' . $phrase);
    }
}
