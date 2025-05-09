<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\DataCollector\Proxy;

use Isolated\Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Isolated\Symfony\Component\Form\FormTypeInterface;
use Isolated\Symfony\Component\Form\ResolvedFormTypeFactoryInterface;
use Isolated\Symfony\Component\Form\ResolvedFormTypeInterface;
/**
 * Proxy that wraps resolved types into {@link ResolvedTypeDataCollectorProxy}
 * instances.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResolvedTypeFactoryDataCollectorProxy implements ResolvedFormTypeFactoryInterface
{
    private $proxiedFactory;
    private $dataCollector;
    public function __construct(ResolvedFormTypeFactoryInterface $proxiedFactory, FormDataCollectorInterface $dataCollector)
    {
        $this->proxiedFactory = $proxiedFactory;
        $this->dataCollector = $dataCollector;
    }
    /**
     * {@inheritdoc}
     */
    public function createResolvedType(FormTypeInterface $type, array $typeExtensions, ResolvedFormTypeInterface $parent = null)
    {
        return new ResolvedTypeDataCollectorProxy($this->proxiedFactory->createResolvedType($type, $typeExtensions, $parent), $this->dataCollector);
    }
}
