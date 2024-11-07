<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Terms;
use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\CallbackTransformer;
use Isolated\Symfony\Component\Form\ChoiceList\ChoiceList;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Isolated\Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isolated\Symfony\Component\Form\Extension\Core\Type\RadioType;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'rating' => '0',
            'label1' => 'Bad',
            'label2' => 'Good',
            'label3' => 'Very good',
            'label4' => 'Excellent',
            'label5' => 'Outstanding',
            'choices' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ],
            'invalid_message' => 'Please select a valid option.',
        ]);

        $resolver->setAllowedTypes('rating', 'string');
        $resolver->setAllowedTypes('label1', 'string');
        $resolver->setAllowedTypes('label2', 'string');
        $resolver->setAllowedTypes('label3', 'string');
        $resolver->setAllowedTypes('label4', 'string');
        $resolver->setAllowedTypes('label5', 'string');
        $resolver->setAllowedTypes('invalid_message', 'string');
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }


    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['rating'] = $options['rating'];
        $view->vars['label1'] = $options['label1'];
        $view->vars['label2'] = $options['label2'];
        $view->vars['label3'] = $options['label3'];
        $view->vars['label4'] = $options['label4'];
        $view->vars['label5'] = $options['label5'];
    }

    public function getBlockPrefix()
    {
        return 'rating';
    }
}
