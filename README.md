<a href="https://github.com/php-type-language" target="_blank">
    <img align="center" src="https://github.com/php-type-language/.github/blob/master/assets/dark.png?raw=true">
</a>

---

<p align="center">
    <a href="https://packagist.org/packages/type-lang/phpdoc-standard-tags"><img src="https://poser.pugx.org/type-lang/phpdoc-standard-tags/require/php?style=for-the-badge" alt="PHP 8.1+"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc-standard-tags"><img src="https://poser.pugx.org/type-lang/phpdoc-standard-tags/version?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/type-lang/phpdoc-standard-tags"><img src="https://poser.pugx.org/type-lang/phpdoc-standard-tags/v/unstable?style=for-the-badge" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/php-type-language/phpdoc-standard-tags/blob/master/LICENSE"><img src="https://poser.pugx.org/type-lang/phpdoc-standard-tags/license?style=for-the-badge" alt="License MIT"></a>
</p>
<p align="center">
    <a href="https://github.com/php-type-language/phpdoc-standard-tags/actions"><img src="https://github.com/php-type-language/phpdoc-standard-tags/workflows/tests/badge.svg"></a>
</p>

Adds support of the PHPDoc standard DocBlock tags.

Read [documentation pages](https://phpdoc.io) for more information.

## Installation

TypeLang PHPDoc Standard Tags is available as Composer repository and can
be installed using the following command in a root of your project:

```sh
$ composer require type-lang/phpdoc-standard-tags
```

## Introduction

Adds support for basic annotations containing descriptions of types 
that affect their output in static analyzers.

- [x] [`@method`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/method.html) — `TypeLang\PHPDoc\Standard\MethodTagFactory`
- [x] [`@param`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/param.html) — `TypeLang\PHPDoc\Standard\ParamTagFactory`
- [x] [`@property`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/property.html) — `TypeLang\PHPDoc\Standard\PropertyTagFactory`
- [x] [`@property-read`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/property-read.html) — `TypeLang\PHPDoc\Standard\PropertyReadTagFactory`
- [x] [`@property-write`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/property-write.html) — `TypeLang\PHPDoc\Standard\PropertyWriteTagFactory`
- [x] [`@return`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/return.html) — `TypeLang\PHPDoc\Standard\ReturnTagFactory`
- [x] [`@throws`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/throws.html) — `TypeLang\PHPDoc\Standard\ThrowsTagFactory`
- [x] [`@var`](https://docs.phpdoc.org/3.0/guide/references/phpdoc/tags/var.html) — `TypeLang\PHPDoc\Standard\VarTagFactory`

## Usage

```php
$tags = new \TypeLang\PHPDoc\Tag\Factory\TagFactory();

// Add support of standard tags
$tags->register('method', new TypeLang\PHPDoc\Standard\MethodTagFactory());
$tags->register('param', new TypeLang\PHPDoc\Standard\ParamTagFactory());
$tags->register('property', new TypeLang\PHPDoc\Standard\PropertyTagFactory());
$tags->register('property-read', new TypeLang\PHPDoc\Standard\PropertyReadTagFactory());
$tags->register('property-write', new TypeLang\PHPDoc\Standard\PropertyWriteTagFactory());
$tags->register('return', new TypeLang\PHPDoc\Standard\ReturnTagFactory());
$tags->register('throws', new TypeLang\PHPDoc\Standard\ThrowsTagFactory());
$tags->register('var', new TypeLang\PHPDoc\Standard\VarTagFactory());

$parser = new TypeLang\PHPDoc\Parser($tags);
$docblock = $parser->parse(<<<'PHPDOC'
    /**
     * @var string example tag.
     */
    PHPDOC);

var_dump($docblock);
```
