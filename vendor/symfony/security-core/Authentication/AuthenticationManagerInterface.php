<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Authentication;

use Isolated\Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Isolated\Symfony\Component\Security\Core\Exception\AuthenticationException;
/**
 * AuthenticationManagerInterface is the interface for authentication managers,
 * which process Token authentication.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal since Symfony 5.3
 */
interface AuthenticationManagerInterface
{
    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @return TokenInterface
     *
     * @throws AuthenticationException if the authentication fails
     */
    public function authenticate(TokenInterface $token);
}
