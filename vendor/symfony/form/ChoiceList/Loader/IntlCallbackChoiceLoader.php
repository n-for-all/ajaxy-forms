<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\ChoiceList\Loader;

/**
 * Callback choice loader optimized for Intl choice types.
 *
 * @author Jules Pietri <jules@heahprod.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class IntlCallbackChoiceLoader extends CallbackChoiceLoader
{
    /**
     * {@inheritdoc}
     */
    public function loadChoicesForValues(array $values, callable $value = null)
    {
        return parent::loadChoicesForValues(\array_filter($values), $value);
    }
    /**
     * {@inheritdoc}
     */
    public function loadValuesForChoices(array $choices, callable $value = null)
    {
        $choices = \array_filter($choices);
        // If no callable is set, choices are the same as values
        if (null === $value) {
            return $choices;
        }
        return parent::loadValuesForChoices($choices, $value);
    }
}
