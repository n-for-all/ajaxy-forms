<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Core\Type;

use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
class HiddenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // hidden fields cannot have a required attribute
            'required' => \false,
            // Pass errors to the parent
            'error_bubbling' => \true,
            'compound' => \false,
            'invalid_message' => function (Options $options, $previousValue) {
                return $options['legacy_error_messages'] ?? \true ? $previousValue : 'The hidden field is invalid.';
            },
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'hidden';
    }
}
