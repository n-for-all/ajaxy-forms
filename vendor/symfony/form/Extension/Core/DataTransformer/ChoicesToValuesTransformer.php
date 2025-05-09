<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Core\DataTransformer;

use Isolated\Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Isolated\Symfony\Component\Form\DataTransformerInterface;
use Isolated\Symfony\Component\Form\Exception\TransformationFailedException;
/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ChoicesToValuesTransformer implements DataTransformerInterface
{
    private $choiceList;
    public function __construct(ChoiceListInterface $choiceList)
    {
        $this->choiceList = $choiceList;
    }
    /**
     * @return array
     *
     * @throws TransformationFailedException if the given value is not an array
     */
    public function transform($array)
    {
        if (null === $array) {
            return [];
        }
        if (!\is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }
        return $this->choiceList->getValuesForChoices($array);
    }
    /**
     * @return array
     *
     * @throws TransformationFailedException if the given value is not an array
     *                                       or if no matching choice could be
     *                                       found for some given value
     */
    public function reverseTransform($array)
    {
        if (null === $array) {
            return [];
        }
        if (!\is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }
        $choices = $this->choiceList->getChoicesForValues($array);
        if (\count($choices) !== \count($array)) {
            throw new TransformationFailedException('Could not find all matching choices for the given values.');
        }
        return $choices;
    }
}
