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

use Isolated\Symfony\Component\Serializer\SerializerInterface;
use Isolated\Twig\Extension\RuntimeExtensionInterface;
/**
 * @author Jesse Rushlow <jr@rushlow.dev>
 */
final class SerializerRuntime implements RuntimeExtensionInterface
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    public function serialize($data, string $format = 'json', array $context = []) : string
    {
        return $this->serializer->serialize($data, $format, $context);
    }
}
