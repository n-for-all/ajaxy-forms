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
use Isolated\Symfony\Component\Form\Extension\Core\DataTransformer\PercentToLocalizedStringTransformer;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormInterface;
use Isolated\Symfony\Component\Form\FormView;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
class PercentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new PercentToLocalizedStringTransformer($options['scale'], $options['type'], $options['rounding_mode'], $options['html5']));
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['symbol'] = $options['symbol'];
        if ($options['html5']) {
            $view->vars['type'] = 'number';
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['scale' => 0, 'rounding_mode' => function (Options $options) {
            trigger_deprecation('symfony/form', '5.1', 'Not configuring the "rounding_mode" option is deprecated. It will default to "\\NumberFormatter::ROUND_HALFUP" in Symfony 6.0.');
            return null;
        }, 'symbol' => '%', 'type' => 'fractional', 'compound' => \false, 'html5' => \false, 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please enter a percentage value.';
        }]);
        $resolver->setAllowedValues('type', ['fractional', 'integer']);
        $resolver->setAllowedValues('rounding_mode', [null, \NumberFormatter::ROUND_FLOOR, \NumberFormatter::ROUND_DOWN, \NumberFormatter::ROUND_HALFDOWN, \NumberFormatter::ROUND_HALFEVEN, \NumberFormatter::ROUND_HALFUP, \NumberFormatter::ROUND_UP, \NumberFormatter::ROUND_CEILING]);
        $resolver->setAllowedTypes('scale', 'int');
        $resolver->setAllowedTypes('symbol', ['bool', 'string']);
        $resolver->setDeprecated('rounding_mode', 'symfony/form', '5.1', function (Options $options, $roundingMode) {
            if (null === $roundingMode) {
                return 'Not configuring the "rounding_mode" option is deprecated. It will default to "\\NumberFormatter::ROUND_HALFUP" in Symfony 6.0.';
            }
            return '';
        });
        $resolver->setAllowedTypes('html5', 'bool');
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'percent';
    }
}
