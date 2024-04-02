<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

class PropertyWriteTagFactory extends PropertyTagFactory
{
    public function create(string $name, string $content, DescriptionParserInterface $descriptions): PropertyWriteTag
    {
        $property = parent::create($name, $content, $descriptions);

        return new PropertyWriteTag(
            name: $property->getName(),
            type: $property->getType(),
            variable: $property->getVariable(),
            description: $property->getDescription(),
        );
    }
}
