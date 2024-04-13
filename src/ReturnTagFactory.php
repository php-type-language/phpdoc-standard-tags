<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Parser as TypesParser;
use TypeLang\Parser\ParserInterface as TypesParserInterface;
use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Factory\FactoryInterface;
use TypeLang\PHPDoc\Tag\Content;

/**
 * This class is responsible for creating "`@return`" tags.
 *
 * See {@see ReturnTag} for details about this tag.
 */
final class ReturnTagFactory implements FactoryInterface
{
    public function __construct(
        private readonly TypesParserInterface $parser = new TypesParser(tolerant: true),
    ) {
        assert($this->parser->tolerant, TypesParser::class . ' must be configured as tolerant');
    }

    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): ReturnTag
    {
        return new ReturnTag(
            name: $name,
            type: $content->nextType($name, $this->parser),
            description: $content->toDescription($descriptions),
        );
    }
}
