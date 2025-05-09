<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Component\Form\Extension\HttpFoundation;

use Isolated\Symfony\Component\Form\AbstractExtension;
/**
 * Integrates the HttpFoundation component with the Form library.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class HttpFoundationExtension extends AbstractExtension
{
    protected function loadTypeExtensions()
    {
        return [new Type\FormTypeHttpFoundationExtension()];
    }
}
