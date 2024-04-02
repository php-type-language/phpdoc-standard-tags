<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\PHPDoc\Parser\Description\DescriptionParserInterface;

class PropertyReadTagFactory extends PropertyTagFactory
{
    public function create(string $name, string $content, DescriptionParserInterface $descriptions): PropertyReadTag
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
