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

use Isolated\Symfony\Bridge\Twig\TokenParser\StopwatchTokenParser;
use Isolated\Symfony\Component\Stopwatch\Stopwatch;
use Isolated\Twig\Extension\AbstractExtension;
use Isolated\Twig\TokenParser\TokenParserInterface;
/**
 * Twig extension for the stopwatch helper.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
final class StopwatchExtension extends AbstractExtension
{
    private $stopwatch;
    private $enabled;
    public function __construct(Stopwatch $stopwatch = null, bool $enabled = \true)
    {
        $this->stopwatch = $stopwatch;
        $this->enabled = $enabled;
    }
    public function getStopwatch() : Stopwatch
    {
        return $this->stopwatch;
    }
    /**
     * @return TokenParserInterface[]
     */
    public function getTokenParsers() : array
    {
        return [
            /*
             * {% stopwatch foo %}
             * Some stuff which will be recorded on the timeline
             * {% endstopwatch %}
             */
            new StopwatchTokenParser(null !== $this->stopwatch && $this->enabled),
        ];
    }
}
