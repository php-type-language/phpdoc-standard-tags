<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\Tag\OptionalVariableNameProviderInterface;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\TypeProviderInterface;

class VarTag extends Tag implements
    TypeProviderInterface,
    OptionalVariableNameProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $variable
     */
    public function __construct(
        string $name,
        protected readonly TypeStatement $type,
        protected readonly ?string $variable = null,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): TypeStatement
    {
        return $this->type;
    }

    public function getVariable(): ?string
    {
        return $this->variable;
    }
}
