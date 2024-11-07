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
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\Form\SubmitButtonTypeInterface;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * A submit button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SubmitType extends AbstractType implements SubmitButtonTypeInterface
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['clicked'] = $form->isClicked();
        if (!$options['validate']) {
            $view->vars['attr']['formnovalidate'] = \true;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('validate', \true);
        $resolver->setAllowedTypes('validate', 'bool');
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ButtonType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'submit';
    }
}
