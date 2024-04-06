<?php

declare(strict_types=1);

namespace TypeLang\PHPDoc\Standard;

use TypeLang\Parser\Node\Stmt\TypeStatement;
use TypeLang\PHPDoc\Tag\OptionalTypeProviderInterface;
use TypeLang\PHPDoc\Tag\Tag;
use TypeLang\PHPDoc\Tag\VariableNameProviderInterface;

/**
 * With the "`@param`" tag it is possible to document the type and the intent of
 * a single argument of a function or method. When provided it MAY contain a
 * type to indicate what is expected. The name of the argument MUST be present
 * so that it is clear which argument this tag relates to.
 *
 * The description is OPTIONAL yet RECOMMENDED, for instance, in case of
 * complicated structures, such as associative arrays.
 *
 * The "`@param`" tag MAY have a multi-line description and does not need
 * explicit delimiting.
 *
 * At a minimum, it is RECOMMENDED to use this tag with each argument whose code
 * signature does not provide type information.
 *
 * This tag MUST NOT occur more than once per argument in a PHPDoc and is
 * limited to structural elements of type method or function.
 *
 * ```
 * * @param [<Type>] $<name> [<description>]
 * ```
 */
class ParamTag extends Tag implements
    OptionalTypeProviderInterface,
    VariableNameProviderInterface
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $varName
     */
    public function __construct(
        string $name,
        protected readonly ?TypeStatement $type,
        protected readonly string $varName,
        \Stringable|string|null $description = null
    ) {
        parent::__construct($name, $description);
    }

    public function getType(): ?TypeStatement
    {
        return $this->type;
    }

    public function getVariableName(): string
    {
        return $this->varName;
    }
}
