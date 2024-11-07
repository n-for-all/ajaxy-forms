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

use Isolated\Symfony\Component\Form\ButtonTypeInterface;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * A form button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ButtonType extends BaseType implements ButtonTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'button';
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('auto_initialize', \false);
    }
}
