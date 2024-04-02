<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;

class PropertyTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(
            tolerant: true,
            conditional: false,
            callables: false,
        ),
    ) {}

    public function create(string $name, string $content, DescriptionParserInterface $descriptions): PropertyTag
    {
        $content = StandardTagLexer::new($content);

        $type = null;

        if (!$content->startsWith('$')) {
            $type = $content->nextType($name, $this->parser);
        }

        $variable = $content->nextVariable($name);

        return new PropertyTag(
            name: $name,
            type: $type,
            variable: $variable,
            description: $content->toDescription($descriptions),
        );
    }
}
