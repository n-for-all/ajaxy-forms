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

use Isolated\Symfony\Component\HttpKernel\Controller\ControllerReference;
use Isolated\Twig\Extension\AbstractExtension;
use Isolated\Twig\TwigFunction;
/**
 * Provides integration with the HttpKernel component.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class HttpKernelExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions() : array
    {
        return [new TwigFunction('render', [HttpKernelRuntime::class, 'renderFragment'], ['is_safe' => ['html']]), new TwigFunction('render_*', [HttpKernelRuntime::class, 'renderFragmentStrategy'], ['is_safe' => ['html']]), new TwigFunction('fragment_uri', [HttpKernelRuntime::class, 'generateFragmentUri']), new TwigFunction('controller', static::class . '::controller')];
    }
    public static function controller(string $controller, array $attributes = [], array $query = []) : ControllerReference
    {
        return new ControllerReference($controller, $attributes, $query);
    }
}
