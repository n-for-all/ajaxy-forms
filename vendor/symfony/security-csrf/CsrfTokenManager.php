<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Csrf;

use Isolated\Symfony\Component\HttpFoundation\RequestStack;
use Isolated\Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Isolated\Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Isolated\Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Isolated\Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Isolated\Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
/**
 * Default implementation of {@link CsrfTokenManagerInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class CsrfTokenManager implements CsrfTokenManagerInterface
{
    private $generator;
    private $storage;
    private $namespace;
    /**
     * @param string|RequestStack|callable|null $namespace
     *                                                     * null: generates a namespace using $_SERVER['HTTPS']
     *                                                     * string: uses the given string
     *                                                     * RequestStack: generates a namespace using the current main request
     *                                                     * callable: uses the result of this callable (must return a string)
     */
    public function __construct(?TokenGeneratorInterface $generator = null, ?TokenStorageInterface $storage = null, $namespace = null)
    {
        $this->generator = $generator ?? new UriSafeTokenGenerator();
        $this->storage = $storage ?? new NativeSessionTokenStorage();
        $superGlobalNamespaceGenerator = function () {
            return !empty($_SERVER['HTTPS']) && 'off' !== \strtolower($_SERVER['HTTPS']) ? 'https-' : '';
        };
        if (null === $namespace) {
            $this->namespace = $superGlobalNamespaceGenerator;
        } elseif ($namespace instanceof RequestStack) {
            $this->namespace = function () use($namespace, $superGlobalNamespaceGenerator) {
                if ($request = $namespace->getMainRequest()) {
                    return $request->isSecure() ? 'https-' : '';
                }
                return $superGlobalNamespaceGenerator();
            };
        } elseif (\is_callable($namespace) || \is_string($namespace)) {
            $this->namespace = $namespace;
        } else {
            throw new InvalidArgumentException(\sprintf('$namespace must be a string, a callable returning a string, null or an instance of "RequestStack". "%s" given.', \get_debug_type($namespace)));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getToken(string $tokenId)
    {
        $namespacedId = $this->getNamespace() . $tokenId;
        if ($this->storage->hasToken($namespacedId)) {
            $value = $this->storage->getToken($namespacedId);
        } else {
            $value = $this->generator->generateToken();
            $this->storage->setToken($namespacedId, $value);
        }
        return new CsrfToken($tokenId, $this->randomize($value));
    }
    /**
     * {@inheritdoc}
     */
    public function refreshToken(string $tokenId)
    {
        $namespacedId = $this->getNamespace() . $tokenId;
        $value = $this->generator->generateToken();
        $this->storage->setToken($namespacedId, $value);
        return new CsrfToken($tokenId, $this->randomize($value));
    }
    /**
     * {@inheritdoc}
     */
    public function removeToken(string $tokenId)
    {
        return $this->storage->removeToken($this->getNamespace() . $tokenId);
    }
    /**
     * {@inheritdoc}
     */
    public function isTokenValid(CsrfToken $token)
    {
        $namespacedId = $this->getNamespace() . $token->getId();
        if (!$this->storage->hasToken($namespacedId)) {
            return \false;
        }
        return \hash_equals($this->storage->getToken($namespacedId), $this->derandomize($token->getValue()));
    }
    private function getNamespace() : string
    {
        return \is_callable($ns = $this->namespace) ? $ns() : $ns;
    }
    private function randomize(string $value) : string
    {
        $key = \random_bytes(32);
        $value = $this->xor($value, $key);
        return \sprintf('%s.%s.%s', \substr(\md5($key), 0, 1 + \ord($key[0]) % 32), \rtrim(\strtr(\base64_encode($key), '+/', '-_'), '='), \rtrim(\strtr(\base64_encode($value), '+/', '-_'), '='));
    }
    private function derandomize(string $value) : string
    {
        $parts = \explode('.', $value);
        if (3 !== \count($parts)) {
            return $value;
        }
        $key = \base64_decode(\strtr($parts[1], '-_', '+/'));
        if ('' === $key || \false === $key) {
            return $value;
        }
        $value = \base64_decode(\strtr($parts[2], '-_', '+/'));
        return $this->xor($value, $key);
    }
    private function xor(string $value, string $key) : string
    {
        if (\strlen($value) > \strlen($key)) {
            $key = \str_repeat($key, \ceil(\strlen($value) / \strlen($key)));
        }
        return $value ^ $key;
    }
}
