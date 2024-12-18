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
/**
 * The TokenStorageInterface.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface TokenStorageInterface
{
    /**
     * Returns the current security token.
     *
     * @return TokenInterface|null
     */
    public function getToken();
    /**
     * Sets the authentication token.
     *
     * @param TokenInterface|null $token A TokenInterface token, or null if no further authentication information should be stored
     */
    public function setToken(?TokenInterface $token = null);
}
