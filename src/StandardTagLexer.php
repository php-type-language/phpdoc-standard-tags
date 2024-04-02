<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\DescriptionInterface;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal TypeLang\PHPDoc\Standard
 */
final class StandardTagLexer
{
    private readonly string $original;

    /**
     * @var int<0, max>
     */
    private int $offset = 0;

    public function __construct(
        private string $content,
    ) {
        $this->original = $this->content;
    }

    /**
     * @param non-empty-string $needle
     */
    public function startsWith(string $needle): bool
    {
        return \str_starts_with($this->content, $needle);
    }

    public static function new(string $content): self
    {
        return new self($content);
    }

    private function slice(int $offset): void
    {
        $size = \strlen($this->content);

        $this->content = \ltrim(\substr(
            string: $this->content,
            offset: $offset,
        ));

        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->offset += $size - \strlen($this->content);
    }

    private function getTagError(string $message, \Throwable $previous = null): InvalidTagException
    {
        return new InvalidTagException(
            source: $this->original,
            offset: $this->offset,
            message: $message,
            previous: $previous,
        );
    }

    /**
     * @param non-empty-string  $tag
     * @throws InvalidTagException
     * @throws \Throwable
     */
    public function nextType(string $tag, TypesParserInterface $parser): TypeStatement
    {
        try {
            $type = $parser->parse($this->content);
        } catch (ParserExceptionInterface $e) {
            throw $this->getTagError(
                message: \sprintf('Tag @%s contains an incorrect type', $tag),
                previous: $e,
            );
        }

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress NoInterfaceProperties
         */
        $this->slice($parser->lastProcessedTokenOffset);

        return $type;
    }

    /**
     * @param non-empty-string $tag
     * @return non-empty-string
     * @throws InvalidTagException
     */
    public function nextVariable(string $tag): string
    {
        return $this->nextOptionalVariable()
            ?? throw $this->getTagError(\sprintf('Tag @%s contains an incorrect variable name', $tag));
    }

    /**
     * @return non-empty-string|null
     */
    public function nextOptionalVariable(): ?string
    {
        if (!\str_starts_with($this->content, '$')) {
            return null;
        }

        \preg_match('/\$([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\b/u', $this->content, $matches);

        if (\count($matches) !== 2 || $matches[1] === '') {
            return null;
        }

        $this->slice(\strlen($matches[0]));

        return $matches[1];
    }

    public function toDescription(DescriptionParserInterface $descriptions): DescriptionInterface
    {
        return $descriptions->parse($this->content);
    }
}
