<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Bridge\Twig\Extension;

use Isolated\Twig\Extension\AbstractExtension;
use Isolated\Twig\TwigFunction;
/**
 * @author Christian Flothmann <christian.flothmann@sensiolabs.de>
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
final class CsrfExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions() : array
    {
        return [new TwigFunction('csrf_token', [CsrfRuntime::class, 'getCsrfToken'])];
    }
}
