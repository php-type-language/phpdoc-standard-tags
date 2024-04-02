<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\Tag\TypedTag;
use TypeLang\PHPDoc\Tag\VariableNameProviderInterface;

class ParamTag extends TypedTag implements VariableNameProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $variable
     */
    public function __construct(
        string $name,
        TypeStatement $type,
        protected readonly string $variable,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $type, $description);
    }

    public function getVariable(): string
    {
        return $this->variable;
    }
}
