<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Content;

/**
 * This class is responsible for creating "@param" tags.
 *
 * See {@see ParamTag} for details about this tag.
 */
final class ParamTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {}

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): ParamTag
    {
        $type = null;

        if (!\str_starts_with($content->value, '$')) {
            $type = $content->nextType($name, $this->parser);
        }

        $variable = $content->nextVariable($name);

        return new ParamTag(
            name: $name,
            type: $type,
            variable: $variable,
            description: $content->toDescription($descriptions),
        );
    }
}
