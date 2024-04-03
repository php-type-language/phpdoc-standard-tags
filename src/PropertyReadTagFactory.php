<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;
use TypeLang\PHPDoc\Tag\Content;

/**
 * This class is responsible for creating "`@property-read`" tags.
 *
 * See {@see PropertyReadTag} for details about this tag.
 */
final class PropertyReadTagFactory extends PropertyTagFactory
{
    public function create(string $name, Content $content, DescriptionParserInterface $descriptions): PropertyReadTag
    {
        $property = parent::create($name, $content, $descriptions);

        return new PropertyReadTag(
            name: $property->getName(),
            type: $property->getType(),
            variable: $property->getVariable(),
            description: $property->getDescription(),
        );
    }
}
