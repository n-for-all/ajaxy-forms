<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Security\Core\Authorization\Voter;

use Isolated\Symfony\Component\ExpressionLanguage\Expression;
use Isolated\Symfony\Component\HttpFoundation\Request;
use Isolated\Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Isolated\Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Isolated\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Isolated\Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Isolated\Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
/**
 * ExpressionVoter votes based on the evaluation of an expression.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ExpressionVoter implements CacheableVoterInterface
{
    private $expressionLanguage;
    private $trustResolver;
    private $authChecker;
    private $roleHierarchy;
    public function __construct(ExpressionLanguage $expressionLanguage, AuthenticationTrustResolverInterface $trustResolver, AuthorizationCheckerInterface $authChecker, ?RoleHierarchyInterface $roleHierarchy = null)
    {
        $this->expressionLanguage = $expressionLanguage;
        $this->trustResolver = $trustResolver;
        $this->authChecker = $authChecker;
        $this->roleHierarchy = $roleHierarchy;
    }
    public function supportsAttribute(string $attribute) : bool
    {
        return \false;
    }
    public function supportsType(string $subjectType) : bool
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        $variables = null;
        foreach ($attributes as $attribute) {
            if (!$attribute instanceof Expression) {
                continue;
            }
            if (null === $variables) {
                $variables = $this->getVariables($token, $subject);
            }
            $result = VoterInterface::ACCESS_DENIED;
            if ($this->expressionLanguage->evaluate($attribute, $variables)) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }
        return $result;
    }
    private function getVariables(TokenInterface $token, $subject) : array
    {
        $roleNames = $token->getRoleNames();
        if (null !== $this->roleHierarchy) {
            $roleNames = $this->roleHierarchy->getReachableRoleNames($roleNames);
        }
        $variables = ['token' => $token, 'user' => $token->getUser(), 'object' => $subject, 'subject' => $subject, 'role_names' => $roleNames, 'trust_resolver' => $this->trustResolver, 'auth_checker' => $this->authChecker];
        // this is mainly to propose a better experience when the expression is used
        // in an access control rule, as the developer does not know that it's going
        // to be handled by this voter
        if ($subject instanceof Request) {
            $variables['request'] = $subject;
        }
        return $variables;
    }
}
