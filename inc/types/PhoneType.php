<?php

namespace Ajaxy\Forms\Inc\Types;

use Ajaxy\Forms\Inc\Types\Data\Countries;
use Ajaxy\Forms\Inc\Types\Transformer\PhoneTransformer;
use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Isolated\Symfony\Component\Form\Extension\Core\Type\TelType;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneType.
 *
 * A phone number entered as a country and a national number, stored as a
 * single string in international format, +971501234567.
 */
class PhoneType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', ChoiceType::class, [
                'choices' => Countries::get_choices(),
                'preferred_choices' => $this->get_preferred_countries($options),
                // Without a default there is nothing sensible to preselect,
                // so the visitor is asked to pick one.
                'placeholder' => $options['default_country'] ? false : 'Country',
                'required' => $options['required'],
                'disabled' => $options['disabled'],
                'label' => false,
                'attr' => ['class' => 'phone-country'],
            ])
            ->add('number', TelType::class, [
                'required' => $options['required'],
                'disabled' => $options['disabled'],
                'label' => false,
                'attr' => \array_merge(
                    ['class' => 'phone-number'],
                    $options['number_placeholder'] ? ['placeholder' => $options['number_placeholder']] : []
                ),
            ])
            ->addModelTransformer(new PhoneTransformer($options['default_country']));
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['default_country'] = $options['default_country'];
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('default_country', '')
            ->setDefault('preferred_countries', '')
            ->setDefault('number_placeholder', '')
            ->setDefault('error_bubbling', false)
            ->setDefault('compound', true)
            ->setAllowedTypes('default_country', ['string', 'null'])
            ->setAllowedTypes('preferred_countries', ['string', 'array', 'null'])
            ->setAllowedTypes('number_placeholder', ['string', 'null']);

        $resolver
            ->setDefault('invalid_message', 'Please enter a valid phone number.');
    }

    /**
     * The countries listed above the rest of the list
     *
     * @param array $options
     *
     * @return array
     */
    private function get_preferred_countries(array $options)
    {
        $preferred = $options['preferred_countries'];
        if (!\is_array($preferred)) {
            $preferred = \array_filter(\array_map('trim', \explode(',', (string) $preferred)));
        }

        if ($options['default_country']) {
            \array_unshift($preferred, $options['default_country']);
        }

        return \array_values(\array_unique(\array_filter(\array_map(function ($code) {
            $code = \strtoupper((string) $code);

            return Countries::has($code) ? $code : null;
        }, $preferred))));
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'phone';
    }
}
