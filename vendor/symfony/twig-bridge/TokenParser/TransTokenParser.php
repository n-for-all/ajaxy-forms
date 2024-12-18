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

use Isolated\Symfony\Bridge\Twig\Node\TransNode;
use Isolated\Twig\Error\SyntaxError;
use Isolated\Twig\Node\Expression\AbstractExpression;
use Isolated\Twig\Node\Expression\ArrayExpression;
use Isolated\Twig\Node\Node;
use Isolated\Twig\Node\TextNode;
use Isolated\Twig\Token;
use Isolated\Twig\TokenParser\AbstractTokenParser;
/**
 * Token Parser for the 'trans' tag.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class TransTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token) : Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $count = null;
        $vars = new ArrayExpression([], $lineno);
        $domain = null;
        $locale = null;
        if (!$stream->test(Token::BLOCK_END_TYPE)) {
            if ($stream->test('count')) {
                // {% trans count 5 %}
                $stream->next();
                $count = $this->parser->getExpressionParser()->parseExpression();
            }
            if ($stream->test('with')) {
                // {% trans with vars %}
                $stream->next();
                $vars = $this->parser->getExpressionParser()->parseExpression();
            }
            if ($stream->test('from')) {
                // {% trans from "messages" %}
                $stream->next();
                $domain = $this->parser->getExpressionParser()->parseExpression();
            }
            if ($stream->test('into')) {
                // {% trans into "fr" %}
                $stream->next();
                $locale = $this->parser->getExpressionParser()->parseExpression();
            } elseif (!$stream->test(Token::BLOCK_END_TYPE)) {
                throw new SyntaxError('Unexpected token. Twig was looking for the "with", "from", or "into" keyword.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }
        // {% trans %}message{% endtrans %}
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideTransFork'], \true);
        if (!$body instanceof TextNode && !$body instanceof AbstractExpression) {
            throw new SyntaxError('A message inside a trans tag must be a simple text.', $body->getTemplateLine(), $stream->getSourceContext());
        }
        $stream->expect(Token::BLOCK_END_TYPE);
        return new TransNode($body, $domain, $count, $vars, $locale, $lineno, $this->getTag());
    }
    public function decideTransFork(Token $token) : bool
    {
        return $token->test(['endtrans']);
    }
    /**
     * {@inheritdoc}
     */
    public function getTag() : string
    {
        return 'trans';
    }
}
