<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Authentication\RememberMe;

use Isolated\Symfony\Component\Security\Core\Exception\TokenNotFoundException;
/**
 * This class is used for testing purposes, and is not really suited for production.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class InMemoryTokenProvider implements TokenProviderInterface
{
    private $tokens = [];
    /**
     * {@inheritdoc}
     */
    public function loadTokenBySeries(string $series)
    {
        if (!isset($this->tokens[$series])) {
            throw new TokenNotFoundException('No token found.');
        }
        return $this->tokens[$series];
    }
    /**
     * {@inheritdoc}
     */
    public function updateToken(string $series, string $tokenValue, \DateTime $lastUsed)
    {
        if (!isset($this->tokens[$series])) {
            throw new TokenNotFoundException('No token found.');
        }
        $token = new PersistentToken($this->tokens[$series]->getClass(), \method_exists($this->tokens[$series], 'getUserIdentifier') ? $this->tokens[$series]->getUserIdentifier() : $this->tokens[$series]->getUsername(), $series, $tokenValue, $lastUsed);
        $this->tokens[$series] = $token;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteTokenBySeries(string $series)
    {
        unset($this->tokens[$series]);
    }
    /**
     * {@inheritdoc}
     */
    public function createNewToken(PersistentTokenInterface $token)
    {
        $this->tokens[$token->getSeries()] = $token;
    }
}
