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
use Isolated\Symfony\Component\Form\Extension\Core\EventListener\FixUrlProtocolListener;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
class UrlType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null !== $options['default_protocol']) {
            $builder->addEventSubscriber(new FixUrlProtocolListener($options['default_protocol']));
        }
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['default_protocol']) {
            $view->vars['attr']['inputmode'] = 'url';
            $view->vars['type'] = 'text';
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['default_protocol' => 'http', 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please enter a valid URL.';
        }]);
        $resolver->setAllowedTypes('default_protocol', ['null', 'string']);
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'url';
    }
}
