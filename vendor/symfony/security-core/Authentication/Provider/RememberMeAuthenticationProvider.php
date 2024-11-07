<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Authentication\Provider;

use Isolated\Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Isolated\Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Isolated\Symfony\Component\Security\Core\Exception\AuthenticationException;
use Isolated\Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Isolated\Symfony\Component\Security\Core\Exception\LogicException;
use Isolated\Symfony\Component\Security\Core\User\UserCheckerInterface;
use Isolated\Symfony\Component\Security\Core\User\UserInterface;
trigger_deprecation('symfony/security-core', '5.3', 'The "%s" class is deprecated, use the new authenticator system instead.', RememberMeAuthenticationProvider::class);
/**
 * @deprecated since Symfony 5.3, use the new authenticator system instead
 */
class RememberMeAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userChecker;
    private $secret;
    private $providerKey;
    /**
     * @param string $secret      A secret
     * @param string $providerKey A provider secret
     */
    public function __construct(UserCheckerInterface $userChecker, string $secret, string $providerKey)
    {
        $this->userChecker = $userChecker;
        $this->secret = $secret;
        $this->providerKey = $providerKey;
    }
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            throw new AuthenticationException('The token is not supported by this authentication provider.');
        }
        if ($this->secret !== $token->getSecret()) {
            throw new BadCredentialsException('The presented secret does not match.');
        }
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            throw new LogicException(\sprintf('Method "%s::getUser()" must return a "%s" instance, "%s" returned.', \get_debug_type($token), UserInterface::class, \get_debug_type($user)));
        }
        $this->userChecker->checkPreAuth($user);
        $this->userChecker->checkPostAuth($user);
        $authenticatedToken = new RememberMeToken($user, $this->providerKey, $this->secret);
        $authenticatedToken->setAttributes($token->getAttributes());
        return $authenticatedToken;
    }
    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof RememberMeToken && $token->getFirewallName() === $this->providerKey;
    }
}
