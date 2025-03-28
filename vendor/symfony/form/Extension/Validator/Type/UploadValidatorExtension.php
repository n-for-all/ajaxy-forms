<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\Validator\Type;

use Isolated\Symfony\Component\Form\AbstractTypeExtension;
use Isolated\Symfony\Component\Form\Extension\Core\Type\FormType;
use Isolated\Symfony\Component\OptionsResolver\Options;
use Isolated\Symfony\Component\OptionsResolver\OptionsResolver;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 * @author David Badura <d.a.badura@gmail.com>
 */
class UploadValidatorExtension extends AbstractTypeExtension
{
    private $translator;
    private $translationDomain;
    public function __construct(TranslatorInterface $translator, string $translationDomain = null)
    {
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $translator = $this->translator;
        $translationDomain = $this->translationDomain;
        $resolver->setNormalizer('upload_max_size_message', function (Options $options, $message) use($translator, $translationDomain) {
            return function () use($translator, $translationDomain, $message) {
                return $translator->trans($message(), [], $translationDomain);
            };
        });
    }
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes() : iterable
    {
        return [FormType::class];
    }
}
