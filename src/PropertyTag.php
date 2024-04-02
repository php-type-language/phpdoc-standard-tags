<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\VariableNameProviderInterface;

class PropertyTag extends Tag implements
    OptionalTypeProviderInterface,
    VariableNameProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $variable
     */
    public function __construct(
        string $name,
        protected readonly ?TypeStatement $type,
        protected readonly string $variable,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): ?TypeStatement
    {
        return $this->type;
    }

    public function getVariable(): string
    {
        return $this->variable;
    }
}
