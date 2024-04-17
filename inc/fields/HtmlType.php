<?php

namespace Ajaxy\Forms\Inc\Fields;


use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'html' => '',
            'label' => false
        ));
    }

    /**
     * Get block prefix
     *
     * @date 2022-05-21
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'html';
    }

    /**
     * Build the view
     *
     * @date 2022-05-21
     *
     * @return void
     */
    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        $view->vars['html'] = $options['html'];
        return parent::buildView($view,  $form,  $options);
    }

    /**
     * Get the type
     *
     * @date 2022-05-21
     *
     * @return string
     */
    public function getType()
    {
        return 'html';
    }

    /**
     * Validate the view, always true
     *
     * @date 2022-05-21
     *
     * @param string  $data
     * @param boolean $clearMissing
     *
     * @return void
     */
    public function validate($data, $clearMissing = false)
    {
        return true;
    }
}
