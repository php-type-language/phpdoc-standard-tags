<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\Tag\OptionalVariableNameProviderInterface;
use TypeLang\PHPDoc\Tag\TypedTag;

class VarTag extends TypedTag implements OptionalVariableNameProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $variable
     */
    public function __construct(
        string $name,
        TypeStatement $type,
        protected readonly ?string $variable = null,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $type, $description);
    }

    public function getVariable(): ?string
    {
        return $this->variable;
    }
}
