<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Isolated\Symfony\Polyfill\Intl\Icu\Exception;

/**
 * RuntimeException for the Intl component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
