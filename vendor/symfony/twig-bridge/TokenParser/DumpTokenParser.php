<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Bridge\Twig\TokenParser;

use Isolated\Symfony\Bridge\Twig\Node\DumpNode;
use Isolated\Twig\Node\Node;
use Isolated\Twig\Token;
use Isolated\Twig\TokenParser\AbstractTokenParser;
/**
 * Token Parser for the 'dump' tag.
 *
 * Dump variables with:
 *
 *     {% dump %}
 *     {% dump foo %}
 *     {% dump foo, bar %}
 *
 * @author Julien Galenski <julien.galenski@gmail.com>
 */
final class DumpTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token) : Node
    {
        $values = null;
        if (!$this->parser->getStream()->test(Token::BLOCK_END_TYPE)) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();
        }
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        return new DumpNode($this->parser->getVarName(), $values, $token->getLine(), $this->getTag());
    }
    /**
     * {@inheritdoc}
     */
    public function getTag() : string
    {
        return 'dump';
    }
}
