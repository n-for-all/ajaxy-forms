<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\ChoiceList\Factory;

use Isolated\Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Isolated\Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Isolated\Symfony\Component\Form\ChoiceList\View\ChoiceListView;
use Isolated\Symfony\Contracts\Service\ResetInterface;
/**
 * Caches the choice lists created by the decorated factory.
 *
 * To cache a list based on its options, arguments must be decorated
 * by a {@see Cache\AbstractStaticOption} implementation.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Jules Pietri <jules@heahprod.com>
 */
class CachingFactoryDecorator implements ChoiceListFactoryInterface, ResetInterface
{
    private $decoratedFactory;
    /**
     * @var ChoiceListInterface[]
     */
    private $lists = [];
    /**
     * @var ChoiceListView[]
     */
    private $views = [];
    /**
     * Generates a SHA-256 hash for the given value.
     *
     * Optionally, a namespace string can be passed. Calling this method will
     * the same values, but different namespaces, will return different hashes.
     *
     * @param mixed $value The value to hash
     *
     * @return string The SHA-256 hash
     *
     * @internal
     */
    public static function generateHash($value, string $namespace = '') : string
    {
        if (\is_object($value)) {
            $value = \spl_object_hash($value);
        } elseif (\is_array($value)) {
            \array_walk_recursive($value, function (&$v) {
                if (\is_object($v)) {
                    $v = \spl_object_hash($v);
                }
            });
        }
        return \hash('sha256', $namespace . ':' . \serialize($value));
    }
    public function __construct(ChoiceListFactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }
    /**
     * Returns the decorated factory.
     *
     * @return ChoiceListFactoryInterface
     */
    public function getDecoratedFactory()
    {
        return $this->decoratedFactory;
    }
    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param mixed $filter
     */
    public function createListFromChoices(iterable $choices, $value = null)
    {
        $filter = \func_num_args() > 2 ? \func_get_arg(2) : null;
        if ($choices instanceof \Traversable) {
            $choices = \iterator_to_array($choices);
        }
        $cache = \true;
        // Only cache per value and filter when needed. The value is not validated on purpose.
        // The decorated factory may decide which values to accept and which not.
        if ($value instanceof Cache\ChoiceValue) {
            $value = $value->getOption();
        } elseif ($value) {
            $cache = \false;
        }
        if ($filter instanceof Cache\ChoiceFilter) {
            $filter = $filter->getOption();
        } elseif ($filter) {
            $cache = \false;
        }
        if (!$cache) {
            return $this->decoratedFactory->createListFromChoices($choices, $value, $filter);
        }
        $hash = self::generateHash([$choices, $value, $filter], 'fromChoices');
        if (!isset($this->lists[$hash])) {
            $this->lists[$hash] = $this->decoratedFactory->createListFromChoices($choices, $value, $filter);
        }
        return $this->lists[$hash];
    }
    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param mixed $filter
     */
    public function createListFromLoader(ChoiceLoaderInterface $loader, $value = null)
    {
        $filter = \func_num_args() > 2 ? \func_get_arg(2) : null;
        $cache = \true;
        if ($loader instanceof Cache\ChoiceLoader) {
            $loader = $loader->getOption();
        } else {
            $cache = \false;
        }
        if ($value instanceof Cache\ChoiceValue) {
            $value = $value->getOption();
        } elseif ($value) {
            $cache = \false;
        }
        if ($filter instanceof Cache\ChoiceFilter) {
            $filter = $filter->getOption();
        } elseif ($filter) {
            $cache = \false;
        }
        if (!$cache) {
            return $this->decoratedFactory->createListFromLoader($loader, $value, $filter);
        }
        $hash = self::generateHash([$loader, $value, $filter], 'fromLoader');
        if (!isset($this->lists[$hash])) {
            $this->lists[$hash] = $this->decoratedFactory->createListFromLoader($loader, $value, $filter);
        }
        return $this->lists[$hash];
    }
    /**
     * {@inheritdoc}
     *
     * @param mixed $preferredChoices
     * @param mixed $label
     * @param mixed $index
     * @param mixed $groupBy
     * @param mixed $attr
     * @param mixed $labelTranslationParameters
     */
    public function createView(ChoiceListInterface $list, $preferredChoices = null, $label = null, $index = null, $groupBy = null, $attr = null)
    {
        $labelTranslationParameters = \func_num_args() > 6 ? \func_get_arg(6) : [];
        $cache = \true;
        if ($preferredChoices instanceof Cache\PreferredChoice) {
            $preferredChoices = $preferredChoices->getOption();
        } elseif ($preferredChoices) {
            $cache = \false;
        }
        if ($label instanceof Cache\ChoiceLabel) {
            $label = $label->getOption();
        } elseif (null !== $label) {
            $cache = \false;
        }
        if ($index instanceof Cache\ChoiceFieldName) {
            $index = $index->getOption();
        } elseif ($index) {
            $cache = \false;
        }
        if ($groupBy instanceof Cache\GroupBy) {
            $groupBy = $groupBy->getOption();
        } elseif ($groupBy) {
            $cache = \false;
        }
        if ($attr instanceof Cache\ChoiceAttr) {
            $attr = $attr->getOption();
        } elseif ($attr) {
            $cache = \false;
        }
        if ($labelTranslationParameters instanceof Cache\ChoiceTranslationParameters) {
            $labelTranslationParameters = $labelTranslationParameters->getOption();
        } elseif ([] !== $labelTranslationParameters) {
            $cache = \false;
        }
        if (!$cache) {
            return $this->decoratedFactory->createView($list, $preferredChoices, $label, $index, $groupBy, $attr, $labelTranslationParameters);
        }
        $hash = self::generateHash([$list, $preferredChoices, $label, $index, $groupBy, $attr, $labelTranslationParameters]);
        if (!isset($this->views[$hash])) {
            $this->views[$hash] = $this->decoratedFactory->createView($list, $preferredChoices, $label, $index, $groupBy, $attr, $labelTranslationParameters);
        }
        return $this->views[$hash];
    }
    public function reset()
    {
        $this->lists = [];
        $this->views = [];
        Cache\AbstractStaticOption::reset();
    }
}
