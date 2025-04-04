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

use Isolated\Symfony\Component\Form\AbstractTypeExtension;
use Isolated\Symfony\Component\Form\Extension\Core\EventListener\TransformationFailureListener;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Christian Flothmann <christian.flothmann@sensiolabs.de>
 */
class TransformationFailureExtension extends AbstractTypeExtension
{
    private $translator;
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['constraints'])) {
            $builder->addEventSubscriber(new TransformationFailureListener($this->translator));
        }
    }
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes() : iterable
    {
        return [FormType::class];
    }
}
