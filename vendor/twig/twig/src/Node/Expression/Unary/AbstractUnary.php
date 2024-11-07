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
namespace Isolated\Twig\Node\Expression\Unary;

use Isolated\Twig\Compiler;
use Isolated\Twig\Node\Expression\AbstractExpression;
use Isolated\Twig\Node\Node;
abstract class AbstractUnary extends AbstractExpression
{
    public function __construct(Node $node, int $lineno)
    {
        parent::__construct(['node' => $node], [], $lineno);
    }
    public function compile(Compiler $compiler) : void
    {
        $compiler->raw(' ');
        $this->operator($compiler);
        $compiler->subcompile($this->getNode('node'));
    }
    public abstract function operator(Compiler $compiler) : Compiler;
}
