<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Validator\Constraints;

use Isolated\Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Isolated\Symfony\Component\Validator\Constraint;
use Isolated\Symfony\Component\Validator\ConstraintValidator;
use Isolated\Symfony\Component\Validator\Exception\UnexpectedTypeException;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@symfony.com>
 */
class ExpressionValidator extends ConstraintValidator
{
    private $expressionLanguage;
    public function __construct(?ExpressionLanguage $expressionLanguage = null)
    {
        $this->expressionLanguage = $expressionLanguage;
    }
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Expression) {
            throw new UnexpectedTypeException($constraint, Expression::class);
        }
        $variables = $constraint->values;
        $variables['value'] = $value;
        $variables['this'] = $this->context->getObject();
        if (!$this->getExpressionLanguage()->evaluate($constraint->expression, $variables)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING))->setCode(Expression::EXPRESSION_FAILED_ERROR)->addViolation();
        }
    }
    private function getExpressionLanguage() : ExpressionLanguage
    {
        if (null === $this->expressionLanguage) {
            $this->expressionLanguage = new ExpressionLanguage();
        }
        return $this->expressionLanguage;
    }
}
