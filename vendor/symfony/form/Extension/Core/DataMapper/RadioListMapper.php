<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Core\DataMapper;

use Isolated\Symfony\Component\Form\DataMapperInterface;
use Isolated\Symfony\Component\Form\Exception\UnexpectedTypeException;
/**
 * Maps choices to/from radio forms.
 *
 * A {@link ChoiceListInterface} implementation is used to find the
 * corresponding string values for the choices. The radio form whose "value"
 * option corresponds to the selected value is marked as selected.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RadioListMapper implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($choice, iterable $radios)
    {
        if (\is_array($radios)) {
            trigger_deprecation('symfony/form', '5.3', 'Passing an array as the second argument of the "%s()" method is deprecated, pass "\\Traversable" instead.', __METHOD__);
        }
        if (!\is_string($choice)) {
            throw new UnexpectedTypeException($choice, 'string');
        }
        foreach ($radios as $radio) {
            $value = $radio->getConfig()->getOption('value');
            $radio->setData($choice === $value);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function mapFormsToData(iterable $radios, &$choice)
    {
        if (\is_array($radios)) {
            trigger_deprecation('symfony/form', '5.3', 'Passing an array as the first argument of the "%s()" method is deprecated, pass "\\Traversable" instead.', __METHOD__);
        }
        if (null !== $choice && !\is_string($choice)) {
            throw new UnexpectedTypeException($choice, 'null or string');
        }
        $choice = null;
        foreach ($radios as $radio) {
            if ($radio->getData()) {
                if ('placeholder' === $radio->getName()) {
                    return;
                }
                $choice = $radio->getConfig()->getOption('value');
                return;
            }
        }
    }
}
