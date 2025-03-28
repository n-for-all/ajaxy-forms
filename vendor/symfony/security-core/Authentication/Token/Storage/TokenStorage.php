<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Authentication\Token\Storage;

use Isolated\Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Isolated\Symfony\Contracts\Service\ResetInterface;
/**
 * TokenStorage contains a TokenInterface.
 *
 * It gives access to the token representing the current user authentication.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class TokenStorage implements TokenStorageInterface, ResetInterface
{
    private $token;
    private $initializer;
    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        if ($initializer = $this->initializer) {
            $this->initializer = null;
            $initializer();
        }
        return $this->token;
    }
    /**
     * {@inheritdoc}
     */
    public function setToken(?TokenInterface $token = null)
    {
        if ($token) {
            // ensure any initializer is called
            $this->getToken();
            // @deprecated since Symfony 5.3
            if (!\method_exists($token, 'getUserIdentifier')) {
                trigger_deprecation('symfony/security-core', '5.3', 'Not implementing method "getUserIdentifier(): string" in token class "%s" is deprecated. This method will replace "getUsername()" in Symfony 6.0.', \get_debug_type($token));
            }
        }
        $this->initializer = null;
        $this->token = $token;
    }
    public function setInitializer(?callable $initializer) : void
    {
        $this->initializer = $initializer;
    }
    public function reset()
    {
        $this->setToken(null);
    }
}
