<?php

namespace Ajaxy\Forms\Inc\Types;

use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RadioGroupType.
 *
 * A set of radio buttons, one choice out of many, as opposed to the single
 * on/off radio of the core radio type.
 */
class RadioGroupType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => true,
            'multiple' => false,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'radio_group';
    }
}
