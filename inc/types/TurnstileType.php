<?php

namespace Ajaxy\Forms\Inc\Types;

use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TurnstileType.
 *
 * Cloudflare Turnstile widget, the privacy friendly alternative to reCAPTCHA.
 *
 * @see https://developers.cloudflare.com/turnstile/
 */
class TurnstileType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new Listener\TurnstileValidationListener($options);
        $builder->addEventSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['key'] = $options['key'];
        $view->vars['theme'] = $options['theme'] ?: 'auto';
        $view->vars['size'] = $options['size'] ?: 'normal';
        $view->vars['appearance'] = $options['appearance'] ?: 'always';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('key', '')
            ->setDefault('secret', '')
            ->setDefault('theme', 'auto')
            ->setDefault('size', 'normal')
            ->setDefault('appearance', 'always')
            ->setAllowedTypes('key', ['string', 'null'])
            ->setAllowedTypes('secret', ['string', 'null'])
            ->setAllowedTypes('theme', ['string', 'null'])
            ->setAllowedTypes('size', ['string', 'null'])
            ->setAllowedTypes('appearance', ['string', 'null']);

        $resolver
            ->setDefault('invalid_message', 'The captcha is invalid. Please try again.');
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'turnstile';
    }
}
