<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Content;

final class ThrowsTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(
            tolerant: true,
            conditional: false,
            callables: false,
        ),
    ) {}

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): ThrowsTag
    {
        return new ThrowsTag(
            name: $name,
            type: $content->nextType($name, $this->parser),
            description: $content->toDescription($descriptions),
        );
    }
}
