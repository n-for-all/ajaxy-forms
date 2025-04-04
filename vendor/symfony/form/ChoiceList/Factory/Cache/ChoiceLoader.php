<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\ChoiceList\Factory\Cache;

use Isolated\Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Isolated\Symfony\Component\Form\FormTypeExtensionInterface;
use Isolated\Symfony\Component\Form\FormTypeInterface;
/**
 * A cacheable wrapper for {@see FormTypeInterface} or {@see FormTypeExtensionInterface}
 * which configures a "choice_loader" option.
 *
 * @internal
 *
 * @author Jules Pietri <jules@heahprod.com>
 */
final class ChoiceLoader extends AbstractStaticOption implements ChoiceLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadChoiceList(callable $value = null) : ChoiceListInterface
    {
        return $this->getOption()->loadChoiceList($value);
    }
    /**
     * {@inheritdoc}
     */
    public function loadChoicesForValues(array $values, callable $value = null) : array
    {
        return $this->getOption()->loadChoicesForValues($values, $value);
    }
    /**
     * {@inheritdoc}
     */
    public function loadValuesForChoices(array $choices, callable $value = null) : array
    {
        return $this->getOption()->loadValuesForChoices($choices, $value);
    }
}
