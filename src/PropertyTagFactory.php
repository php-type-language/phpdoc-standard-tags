<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Content;

/**
 * This class is responsible for creating "`@property`" tags.
 *
 * See {@see PropertyTag} for details about this tag.
 */
final class PropertyTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        assert($this->parser->tolerant, TypesParser::class . ' must be configured as tolerant');
    }

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): PropertyTag
    {
        $type = null;

        if (!\str_starts_with($content->value, '$')) {
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
