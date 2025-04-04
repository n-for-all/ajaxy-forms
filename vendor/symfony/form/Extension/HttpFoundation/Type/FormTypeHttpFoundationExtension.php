<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\HttpFoundation\Type;

use Isolated\Symfony\Component\Form\AbstractTypeExtension;
use Isolated\Symfony\Component\Form\Extension\Core\Type\FormType;
use Isolated\Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Isolated\Symfony\Component\Form\FormBuilderInterface;
use Isolated\Symfony\Component\Form\RequestHandlerInterface;
/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormTypeHttpFoundationExtension extends AbstractTypeExtension
{
    private $requestHandler;
    public function __construct(RequestHandlerInterface $requestHandler = null)
    {
        $this->requestHandler = $requestHandler ?? new HttpFoundationRequestHandler();
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setRequestHandler($this->requestHandler);
    }
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes() : iterable
    {
        return [FormType::class];
    }
}
