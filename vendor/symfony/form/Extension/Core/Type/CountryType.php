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
use Isolated\Symfony\Component\Form\ChoiceList\ChoiceList;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Isolated\Symfony\Component\Form\Exception\LogicException;
use Isolated\Symfony\Component\Intl\Countries;
use Isolated\Symfony\Component\Intl\Intl;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
class CountryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['choice_loader' => function (Options $options) {
            if (!\class_exists(Intl::class)) {
                throw new LogicException(\sprintf('The "symfony/intl" component is required to use "%s". Try running "composer require symfony/intl".', static::class));
            }
            $choiceTranslationLocale = $options['choice_translation_locale'];
            $alpha3 = $options['alpha3'];
            return ChoiceList::loader($this, new IntlCallbackChoiceLoader(function () use($choiceTranslationLocale, $alpha3) {
                return \array_flip($alpha3 ? Countries::getAlpha3Names($choiceTranslationLocale) : Countries::getNames($choiceTranslationLocale));
            }), [$choiceTranslationLocale, $alpha3]);
        }, 'choice_translation_domain' => \false, 'choice_translation_locale' => null, 'alpha3' => \false, 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please select a valid country.';
        }]);
        $resolver->setAllowedTypes('choice_translation_locale', ['null', 'string']);
        $resolver->setAllowedTypes('alpha3', 'bool');
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'country';
    }
}
