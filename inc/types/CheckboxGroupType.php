<?php

namespace Ajaxy\Forms\Inc\Types;

use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckboxGroupType.
 *
 * A set of checkboxes, any number of choices out of many.
 */
class CheckboxGroupType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => true,
            'multiple' => true,
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
        return 'checkbox_group';
    }
}
