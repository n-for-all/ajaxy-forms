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
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\FormError;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
class ColorType extends AbstractType
{
    /**
     * @see https://www.w3.org/TR/html52/sec-forms.html#color-state-typecolor
     */
    private const HTML5_PATTERN = '/^#[0-9a-f]{6}$/i';
    private $translator;
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['html5']) {
            return;
        }
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) : void {
            $value = $event->getData();
            if (null === $value || '' === $value) {
                return;
            }
            if (\is_string($value) && \preg_match(self::HTML5_PATTERN, $value)) {
                return;
            }
            $messageTemplate = 'This value is not a valid HTML5 color.';
            $messageParameters = ['{{ value }}' => \is_scalar($value) ? (string) $value : \gettype($value)];
            $message = $this->translator ? $this->translator->trans($messageTemplate, $messageParameters, 'validators') : $messageTemplate;
            $event->getForm()->addError(new FormError($message, $messageTemplate, $messageParameters));
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['html5' => \false, 'invalid_message' => function (Options $options, $previousValue) {
            return $options['legacy_error_messages'] ?? \true ? $previousValue : 'Please select a valid color.';
        }]);
        $resolver->setAllowedTypes('html5', 'bool');
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'color';
    }
}
