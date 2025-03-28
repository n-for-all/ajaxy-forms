<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\ChoiceList\View;

/**
 * Represents a group of choices in templates.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @implements \IteratorAggregate<array-key, ChoiceGroupView|ChoiceView>
 */
class ChoiceGroupView implements \IteratorAggregate
{
    public $label;
    public $choices;
    /**
     * Creates a new choice group view.
     *
     * @param array<array-key, ChoiceGroupView|ChoiceView> $choices the choice views in the group
     */
    public function __construct(string $label, array $choices = [])
    {
        $this->label = $label;
        $this->choices = $choices;
    }
    /**
     * {@inheritdoc}
     *
     * @return \Traversable<array-key, ChoiceGroupView|ChoiceView>
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->choices);
    }
}
