<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Twig\Node;

use Isolated\Twig\Attribute\YieldReady;
use Isolated\Twig\Compiler;
use Isolated\Twig\Node\Expression\AbstractExpression;
/**
 * Represents a node that outputs an expression.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
#[YieldReady]
class PrintNode extends Node implements NodeOutputInterface
{
    public function __construct(AbstractExpression $expr, int $lineno, ?string $tag = null)
    {
        parent::__construct(['expr' => $expr], [], $lineno, $tag);
    }
    public function compile(Compiler $compiler) : void
    {
        /** @var AbstractExpression */
        $expr = $this->getNode('expr');
        $compiler->addDebugInfo($this)->write($expr->isGenerator() ? 'yield from ' : 'yield ')->subcompile($expr)->raw(";\n");
    }
}
