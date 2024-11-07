<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Core;

use Isolated\Symfony\Component\Form\AbstractExtension;
use Isolated\Symfony\Component\Form\ChoiceList\Factory\CachingFactoryDecorator;
use Isolated\Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Isolated\Symfony\Component\Form\ChoiceList\Factory\DefaultChoiceListFactory;
use Isolated\Symfony\Component\Form\ChoiceList\Factory\PropertyAccessDecorator;
use Isolated\Symfony\Component\Form\Extension\Core\Type\TransformationFailureExtension;
use Isolated\Symfony\Component\PropertyAccess\PropertyAccess;
use Isolated\Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * Represents the main form extension, which loads the core functionality.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CoreExtension extends AbstractExtension
{
    private $propertyAccessor;
    private $choiceListFactory;
    private $translator;
    public function __construct(PropertyAccessorInterface $propertyAccessor = null, ChoiceListFactoryInterface $choiceListFactory = null, TranslatorInterface $translator = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
        $this->choiceListFactory = $choiceListFactory ?? new CachingFactoryDecorator(new PropertyAccessDecorator(new DefaultChoiceListFactory(), $this->propertyAccessor));
        $this->translator = $translator;
    }
    protected function loadTypes()
    {
        return [new Type\FormType($this->propertyAccessor), new Type\BirthdayType(), new Type\CheckboxType(), new Type\ChoiceType($this->choiceListFactory, $this->translator), new Type\CollectionType(), new Type\CountryType(), new Type\DateIntervalType(), new Type\DateType(), new Type\DateTimeType(), new Type\EmailType(), new Type\HiddenType(), new Type\IntegerType(), new Type\LanguageType(), new Type\LocaleType(), new Type\MoneyType(), new Type\NumberType(), new Type\PasswordType(), new Type\PercentType(), new Type\RadioType(), new Type\RangeType(), new Type\RepeatedType(), new Type\SearchType(), new Type\TextareaType(), new Type\TextType(), new Type\TimeType(), new Type\TimezoneType(), new Type\UrlType(), new Type\FileType($this->translator), new Type\ButtonType(), new Type\SubmitType(), new Type\ResetType(), new Type\CurrencyType(), new Type\TelType(), new Type\ColorType($this->translator), new Type\WeekType()];
    }
    protected function loadTypeExtensions()
    {
        return [new TransformationFailureExtension($this->translator)];
    }
}
