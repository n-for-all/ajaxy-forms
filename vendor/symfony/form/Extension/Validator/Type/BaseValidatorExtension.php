<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Validator\Type;

use Isolated\Symfony\Component\Form\AbstractTypeExtension;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
use Isolated\Symfony\Component\Validator\Constraints\GroupSequence;
/**
 * Encapsulates common logic of {@link FormTypeValidatorExtension} and
 * {@link SubmitTypeValidatorExtension}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class BaseValidatorExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Make sure that validation groups end up as null, closure or array
        $validationGroupsNormalizer = function (Options $options, $groups) {
            if (\false === $groups) {
                return [];
            }
            if (empty($groups)) {
                return null;
            }
            if (\is_callable($groups)) {
                return $groups;
            }
            if ($groups instanceof GroupSequence) {
                return $groups;
            }
            return (array) $groups;
        };
        $resolver->setDefaults(['validation_groups' => null]);
        $resolver->setNormalizer('validation_groups', $validationGroupsNormalizer);
    }
}
