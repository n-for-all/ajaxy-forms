<?php

namespace Ajaxy\Forms\Inc\Types;

use ReCaptcha\ReCaptcha;
use Isolated\Symfony\Component\Form\AbstractType;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReCaptchaType.
 */
class ReCaptchaType extends AbstractType
{
    /**
     * ReCaptchaType constructor.
     *
     * @param ReCaptcha $reCaptcha
     */
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subscriber = new Listener\ReCaptchaValidationListener($options);
        $builder->addEventSubscriber($subscriber);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['key'] = $options['key'];
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('key', '')
            ->setDefault('secret', '')
            ->setAllowedTypes('key', 'string')
            ->setAllowedTypes('secret', 'string');

        $resolver
            ->setDefault('invalid_message', 'The captcha is invalid. Please try again.');
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'recaptcha';
    }
}
