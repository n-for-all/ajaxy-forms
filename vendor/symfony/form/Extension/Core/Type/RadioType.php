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
class RadioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please select a valid option.';
        }]);
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'radio';
    }
}
