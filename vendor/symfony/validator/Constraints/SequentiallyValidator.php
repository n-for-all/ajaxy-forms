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

use Isolated\Symfony\Component\Validator\Constraint;
use Isolated\Symfony\Component\Validator\ConstraintValidator;
use Isolated\Symfony\Component\Validator\Exception\UnexpectedTypeException;
/**
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class SequentiallyValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Sequentially) {
            throw new UnexpectedTypeException($constraint, Sequentially::class);
        }
        $context = $this->context;
        $validator = $context->getValidator()->inContext($context);
        $originalCount = $validator->getViolations()->count();
        foreach ($constraint->constraints as $c) {
            if ($originalCount !== $validator->validate($value, $c)->getViolations()->count()) {
                break;
            }
        }
    }
}
