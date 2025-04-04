<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Test;

use Isolated\PHPUnit\Framework\TestCase;
use Isolated\Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Isolated\Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Isolated\Symfony\Component\Security\Core\Authorization\Strategy\AccessDecisionStrategyInterface;
use Isolated\Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
/**
 * Abstract test case for access decision strategies.
 *
 * @author Alexander M. Turek <me@derrabus.de>
 */
abstract class AccessDecisionStrategyTestCase extends TestCase
{
    /**
     * @dataProvider provideStrategyTests
     *
     * @param VoterInterface[] $voters
     */
    public final function testDecide(AccessDecisionStrategyInterface $strategy, array $voters, bool $expected)
    {
        $token = $this->createMock(TokenInterface::class);
        $manager = new AccessDecisionManager($voters, $strategy);
        $this->assertSame($expected, $manager->decide($token, ['ROLE_FOO']));
    }
    /**
     * @return iterable<array{AccessDecisionStrategyInterface, VoterInterface[], bool}>
     */
    public static abstract function provideStrategyTests() : iterable;
    /**
     * @return VoterInterface[]
     */
    protected static final function getVoters(int $grants, int $denies, int $abstains) : array
    {
        $voters = [];
        for ($i = 0; $i < $grants; ++$i) {
            $voters[] = static::getVoter(VoterInterface::ACCESS_GRANTED);
        }
        for ($i = 0; $i < $denies; ++$i) {
            $voters[] = static::getVoter(VoterInterface::ACCESS_DENIED);
        }
        for ($i = 0; $i < $abstains; ++$i) {
            $voters[] = static::getVoter(VoterInterface::ACCESS_ABSTAIN);
        }
        return $voters;
    }
    protected static final function getVoter(int $vote) : VoterInterface
    {
        return new class($vote) implements VoterInterface
        {
            private $vote;
            public function __construct(int $vote)
            {
                $this->vote = $vote;
            }
            public function vote(TokenInterface $token, $subject, array $attributes) : int
            {
                return $this->vote;
            }
        };
    }
}
