<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Terms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepeaterStartType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'compound' => false,
            'label' => false,
            'invalid_message' => 'Please select a valid option.',
        ]);

        $resolver->setAllowedTypes('label', ['string', 'bool']);
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
        return 'repeater_start';
    }

    /**
     * @inheritDoc

     */
    public function buildView(FormView $view, FormInterface $form, array $options) {



        parent::buildView($view, $form, $options);
    }

    public function getBlockPrefix()
    {
        return 'repeater_start';
    }
}
