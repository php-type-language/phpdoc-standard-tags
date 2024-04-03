<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Content;

final class VarTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): VarTag
    {
        $type = $content->nextType($name, $this->parser);
        $variable = $content->nextOptionalVariable();

        return new VarTag(
            name: $name,
            type: $type,
            variable: $variable,
            description: $content->toDescription($descriptions),
        );
    }
}
