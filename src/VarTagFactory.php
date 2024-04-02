<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Exception\ParserExceptionInterface;
use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Exception\InvalidTagException;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\FactoryInterface;

final class VarTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(
            tolerant: true,
            conditional: false,
            callables: false,
        ),
    ) {}

    public function create(string $name, string $content, DescriptionParserInterface $descriptions): VarTag
    {
        try {
            $type = $this->parser->parse($content);
        } catch (ParserExceptionInterface $e) {
            throw new InvalidTagException(
                source: $content,
                offset: 0,
                message: \sprintf('Tag @%s contains an incorrect type', $name),
                previous: $e,
            );
        }

        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress NoInterfaceProperties
         */
        $description = \ltrim(\substr(
            string: $content,
            offset: $this->parser->lastProcessedTokenOffset,
        ));

        return new VarTag(
            name: $name,
            type: $type,
            description: $descriptions->parse($description),
        );
    }
}
