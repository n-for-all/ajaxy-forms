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

use Isolated\Symfony\Bridge\Twig\Node\TransDefaultDomainNode;
use Isolated\Twig\Node\Node;
use Isolated\Twig\Token;
use Isolated\Twig\TokenParser\AbstractTokenParser;
/**
 * Token Parser for the 'trans_default_domain' tag.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class TransDefaultDomainTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token) : Node
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        return new TransDefaultDomainNode($expr, $token->getLine(), $this->getTag());
    }
    /**
     * {@inheritdoc}
     */
    public function getTag() : string
    {
        return 'trans_default_domain';
    }
}
