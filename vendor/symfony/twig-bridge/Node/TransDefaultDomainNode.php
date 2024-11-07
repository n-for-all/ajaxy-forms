<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Bridge\Twig\Node;

use Isolated\Twig\Compiler;
use Isolated\Twig\Node\Expression\AbstractExpression;
use Isolated\Twig\Node\Node;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class TransDefaultDomainNode extends Node
{
    public function __construct(AbstractExpression $expr, int $lineno = 0, string $tag = null)
    {
        parent::__construct(['expr' => $expr], [], $lineno, $tag);
    }
    public function compile(Compiler $compiler) : void
    {
        // noop as this node is just a marker for TranslationDefaultDomainNodeVisitor
    }
}
