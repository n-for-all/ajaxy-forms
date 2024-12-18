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
use Isolated\Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Isolated\Symfony\Component\HttpKernel\Fragment\FragmentUriGeneratorInterface;
/**
 * Provides integration with the HttpKernel component.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class HttpKernelRuntime
{
    private $handler;
    private $fragmentUriGenerator;
    public function __construct(FragmentHandler $handler, FragmentUriGeneratorInterface $fragmentUriGenerator = null)
    {
        $this->handler = $handler;
        $this->fragmentUriGenerator = $fragmentUriGenerator;
    }
    /**
     * Renders a fragment.
     *
     * @param string|ControllerReference $uri A URI as a string or a ControllerReference instance
     *
     * @see FragmentHandler::render()
     */
    public function renderFragment($uri, array $options = []) : string
    {
        $strategy = $options['strategy'] ?? 'inline';
        unset($options['strategy']);
        return $this->handler->render($uri, $strategy, $options);
    }
    /**
     * Renders a fragment.
     *
     * @param string|ControllerReference $uri A URI as a string or a ControllerReference instance
     *
     * @see FragmentHandler::render()
     */
    public function renderFragmentStrategy(string $strategy, $uri, array $options = []) : string
    {
        return $this->handler->render($uri, $strategy, $options);
    }
    public function generateFragmentUri(ControllerReference $controller, bool $absolute = \false, bool $strict = \true, bool $sign = \true) : string
    {
        if (null === $this->fragmentUriGenerator) {
            throw new \LogicException(\sprintf('An instance of "%s" must be provided to use "%s()".', FragmentUriGeneratorInterface::class, __METHOD__));
        }
        return $this->fragmentUriGenerator->generate($controller, null, $absolute, $strict, $sign);
    }
}
