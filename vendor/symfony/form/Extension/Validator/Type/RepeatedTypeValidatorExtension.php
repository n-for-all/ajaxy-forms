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
use Isolated\Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RepeatedTypeValidatorExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Map errors to the first field
        $errorMapping = function (Options $options) {
            return ['.' => $options['first_name']];
        };
        $resolver->setDefaults(['error_mapping' => $errorMapping]);
    }
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes() : iterable
    {
        return [RepeatedType::class];
    }
}
