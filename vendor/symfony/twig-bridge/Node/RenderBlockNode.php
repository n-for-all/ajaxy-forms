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
use Isolated\Twig\Node\Expression\FunctionExpression;
/**
 * Compiles a call to {@link \Symfony\Component\Form\FormRendererInterface::renderBlock()}.
 *
 * The function name is used as block name. For example, if the function name
 * is "foo", the block "foo" will be rendered.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class RenderBlockNode extends FunctionExpression
{
    public function compile(Compiler $compiler) : void
    {
        $compiler->addDebugInfo($this);
        $arguments = \iterator_to_array($this->getNode('arguments'));
        $compiler->write('$this->env->getRuntime(\'Isolated\\Symfony\\Component\\Form\\FormRenderer\')->renderBlock(');
        if (isset($arguments[0])) {
            $compiler->subcompile($arguments[0]);
            $compiler->raw(', \'' . $this->getAttribute('name') . '\'');
            if (isset($arguments[1])) {
                $compiler->raw(', ');
                $compiler->subcompile($arguments[1]);
            }
        }
        $compiler->raw(')');
    }
}
