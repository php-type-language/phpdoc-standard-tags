<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;

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
        $content = StandardTagLexer::new($content);

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
