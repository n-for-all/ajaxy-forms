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
class BirthdayType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['years' => \range((int) \date('Y') - 120, \date('Y')), 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please enter a valid birthdate.';
        }]);
        $resolver->setAllowedTypes('years', 'array');
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'birthday';
    }
}
