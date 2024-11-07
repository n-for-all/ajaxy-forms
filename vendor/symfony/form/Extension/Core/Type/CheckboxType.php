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
use Isolated\Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
class CheckboxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Unlike in other types, where the data is NULL by default, it
        // needs to be a Boolean here. setData(null) is not acceptable
        // for checkboxes and radio buttons (unless a custom model
        // transformer handles this case).
        // We cannot solve this case via overriding the "data" option, because
        // doing so also calls setDataLocked(true).
        $builder->setData($options['data'] ?? \false);
        $builder->addViewTransformer(new BooleanToStringTransformer($options['value'], $options['false_values']));
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = \array_replace($view->vars, ['value' => $options['value'], 'checked' => null !== $form->getViewData()]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $emptyData = function (FormInterface $form, $viewData) {
            return $viewData;
        };
        $resolver->setDefaults(['value' => '1', 'empty_data' => $emptyData, 'compound' => \false, 'false_values' => [null], 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'The checkbox has an invalid value.';
        }, 'is_empty_callback' => static function ($modelData) : bool {
            return \false === $modelData;
        }]);
        $resolver->setAllowedTypes('false_values', 'array');
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'checkbox';
    }
}
