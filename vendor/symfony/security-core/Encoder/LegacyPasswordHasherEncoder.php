<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Encoder;

use Isolated\Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Isolated\Symfony\Component\PasswordHasher\LegacyPasswordHasherInterface;
use Isolated\Symfony\Component\Security\Core\Exception\BadCredentialsException;
/**
 * Forward compatibility for new new PasswordHasher component.
 *
 * @author Alexander M. Turek <me@derrabus.de>
 *
 * @internal To be removed in Symfony 6
 */
final class LegacyPasswordHasherEncoder implements PasswordEncoderInterface
{
    private $passwordHasher;
    public function __construct(LegacyPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function encodePassword(string $raw, ?string $salt) : string
    {
        try {
            return $this->passwordHasher->hash($raw, $salt);
        } catch (InvalidPasswordException $e) {
            throw new BadCredentialsException($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function isPasswordValid(string $encoded, string $raw, ?string $salt) : bool
    {
        return $this->passwordHasher->verify($encoded, $raw, $salt);
    }
    public function needsRehash(string $encoded) : bool
    {
        return $this->passwordHasher->needsRehash($encoded);
    }
}
